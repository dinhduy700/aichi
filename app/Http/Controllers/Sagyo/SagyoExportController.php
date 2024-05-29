<?php

namespace App\Http\Controllers\Sagyo;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsSagyoList;
use App\Helpers\Formatter;
use App\Http\Repositories\SagyoRepository;
use App\Http\Requests\SagyoRequest;

class SagyoExportController extends Controller
{
    protected $sagyoRepository;

    public function __construct(SagyoRepository $sagyoRepository)
    {
        $this->sagyoRepository = $sagyoRepository;
    }

    public function filterForm()
    {
        $initValues = request()->post();

        $initValues['bumon_nm_from']    = $this->sagyoRepository->getBumonNm(data_get($initValues, 'bumon_cd_from'));
        $initValues['bumon_nm_to']      = $this->sagyoRepository->getBumonNm(data_get($initValues, 'bumon_cd_to'));
       
        $tyohyokbnJyomuinOpts           = $this->sagyoRepository->getExportTyohyokbnOpts();
        $injiGroupOpts                  = $this->sagyoRepository->getExportInjiGroupOpts();
        $printOtherOpts                 = $this->sagyoRepository->getExportPrintOtherOpts();

        $EXP_TYOHYO_KBN_ALL             = $this->sagyoRepository::EXP_TYOHYO_KBN_ALL;
        $EXP_TYOHYO_KBN_JYOMUIN_CD      = $this->sagyoRepository::EXP_TYOHYO_KBN_JYOMUIN_CD;
        $EXP_TYOHYO_KBN_YOUSYA_CD       = $this->sagyoRepository::EXP_TYOHYO_KBN_YOUSYA_CD;

        return view('sagyo.exp-filter-form', compact(
            'tyohyokbnJyomuinOpts', 'injiGroupOpts', 'printOtherOpts', 'initValues',
            'EXP_TYOHYO_KBN_ALL', 'EXP_TYOHYO_KBN_JYOMUIN_CD', 'EXP_TYOHYO_KBN_YOUSYA_CD'
        ));
    }

    public function filterValidate(SagyoRequest $request)
    {
        return responseSendForward($this->sagyoRepository->applyRequestToBuilder($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_uriage.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        return response()->download($savePath, '作業指示書_'.  date('YmdHis') . data_get($request->exp, 'tyohyokbn') .'.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_uriage.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = '作業指示書_'. date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $routeNm    = $request->route()->getName();
        $repo       = $this->sagyoRepository;
        $exporter   = new XlsSagyoList();

        if ($routeNm == 'sagyo.exp.pdf') {
            $exporter->setAddDataFuncName('addDataToExcelGroupByPdf');
        }

        $qb                     = $repo->applyRequestToBuilder($request);
        $data                   = $qb->get();
        $arrTmp                 = [];
        $tyohyokbnOpts          = $repo->getExportTyohyokbnOpts();
        $injiGroupOpts          = $repo->getExportInjiGroupOpts();
        $colGroupByTyohyo       = data_get($tyohyokbnOpts, data_get($request->all(), 'exp.tyohyokbn') . '.groupBy');
        $colGroupByInjiGroup    = data_get($injiGroupOpts, data_get($request->all(), 'exp.inji_group') . '.groupBy');
        $colGroupByOtherPrinter = null;
        
        if (in_array($repo::EXP_PRINT_OTHER_GROUP_BY_SYABAN, data_get($request, 'exp.print_other', []))) {
            $colGroupByOtherPrinter = 'syaban';
        }

        // group by collection data, multiple columns
        if (data_get($request->all(),'exp.tyohyokbn') != $repo::EXP_TYOHYO_KBN_ALL) {
            $dataGrouped = $this->prepareDataGroupBy($data, $colGroupByInjiGroup, $colGroupByTyohyo, $colGroupByOtherPrinter, data_get($request->all(),'exp.tyohyokbn'));
        } else {
            $groupJyomuin = $this->prepareDataGroupBy($data, $colGroupByInjiGroup, 'jyomuin_cd', $colGroupByOtherPrinter, $repo::EXP_TYOHYO_KBN_JYOMUIN_CD);
            $arrJyomuin   = $groupJyomuin->toArray();
            foreach ($arrJyomuin as $k => $items) {
                foreach ($items as $i => &$item) {
                    $item['yosya_tyukei_kin'] = '';
                    $arrTmp['jyomuin_cd__' . $k][] = $items[$i];
                }
            }
            
            $groupYousya = $this->prepareDataGroupBy($data, $colGroupByInjiGroup, 'yousya_cd', $colGroupByOtherPrinter, $repo::EXP_TYOHYO_KBN_YOUSYA_CD);
            $arrYousya   = $groupYousya->toArray();
            foreach ($arrYousya as $k => $items) {
                foreach ($items as $i => &$item) {
                    $item['mobile_tel'] = '';
                    $arrTmp['yousya_cd__' . $k][] = $items[$i];
                }
            }
          
            $dataGrouped = $arrTmp;
        }

        $config = require(app_path('Helpers/Excel/config/t_sagyo.php'));

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }

        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        if (!empty(data_get($request->exp, 'print_other'))) {
            //携帯番号 
            if ( (data_get($request->exp, 'tyohyokbn') == $repo::EXP_TYOHYO_KBN_JYOMUIN_CD && (in_array($repo::EXP_PRINT_OTHER_MOBILE_TEL, data_get($request->exp, 'print_other'))))
              || (data_get($request->exp, 'tyohyokbn') == $repo::EXP_TYOHYO_KBN_ALL && (in_array($repo::EXP_PRINT_OTHER_MOBILE_TEL, data_get($request->exp, 'print_other'))))
            ) {
                $config['block'][4]['A'] = ['field' => 'mobile_tel'];
            }
    
            //庸車料
            if ((data_get($request->exp, 'tyohyokbn') == $repo::EXP_TYOHYO_KBN_YOUSYA_CD && in_array($repo::EXP_PRINT_OTHER_YOSYA_TYUKEI_KIN, data_get($request->exp, 'print_other')))
            || (data_get($request->exp, 'tyohyokbn') == $repo::EXP_TYOHYO_KBN_ALL && (in_array($repo::EXP_PRINT_OTHER_YOSYA_TYUKEI_KIN, data_get($request->exp, 'print_other'))))
            ) {
                $config['block'][4]['O'] = ['field' => 'yosya_tyukei_kin'];
            }
        }
        
        $exporter->export(
            app_path('Helpers/Excel/template/t_uriage_sagyo.xlsx'),//template
            $config,
            $dataGrouped,
            $savePath
        );

        return $savePath;
    }

    public function csv(Request $request)
    {
        $repo = $this->sagyoRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_uriage_sagyo.php'));
        $exp->qb = $repo->applyRequestToBuilder($request);
        $injiGroup = data_get($request->exp, 'inji_group');

        $exportHeader = in_array(
            $repo::EXP_PRINT_OTHER_CSV_HEADER,
            data_get($request, 'exp.print_other', [])
        );
        if ($exportHeader) $exp->fputcsv($mapping);//export header


        $exp->exportData(function ($row) use ($mapping, $injiGroup) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }
            $expRow['dt']               = $row[data_get($this->sagyoRepository->getExportInjiGroupOpts(), $injiGroup . '.field')];
            $expRow['yousya_cd_status'] = !empty($row['yousya_cd']) ? 1 : 0;
            $expRow['jikoku_status']    = !empty($row['jikoku']) ? 1 : 0;
            $expRow['syuka_tm_2']       = data_get($row, 'syuka_tm');
            $expRow['jikoku_2']         = data_get($row, 'jikoku');
            $expRow['hachaku_nm_2']     = data_get($row, 'hachaku_nm');


            $dateKeys = [
                'syuka_dt','haitatu_dt','unso_dt', 'nipou_dt',
                //'add_dt', 'upd_dt'
            ];
            foreach ($dateKeys as $key) {
                $expRow[$key] = Formatter::date($row[$key]);
            }

            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, '作業指示書_'.  date('YmdHis').'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }

    public function initDataModalMUserPg(Request $request)
    {
        $qb = DB::table('m_user_pg_function')->where($this->getConfWhereUserPgFunction());

        $mode = $qb->exists() ? 'edit' : 'create';

        return response()->json([
            'config' => Arr::except($this->sagyoRepository->getExportTyohyokbnOpts(), [$this->sagyoRepository::EXP_TYOHYO_KBN_ALL]),
            'record' => $qb->first(),
            'mode' => $mode,
        ]);
    }

    public function handleMUserPg(Request $request)
    {
        $values = [];

        $mode = data_get($request, 'mode');

        $tyohyokbns = $this->sagyoRepository->getExportTyohyokbnOpts();

        $where = $this->getConfWhereUserPgFunction();;

        $colsInsertUserPgFunc = data_get($tyohyokbns, $request->key . '.fieldsUserPgFunc');

        foreach ($colsInsertUserPgFunc as $k => $v) {
            switch ($k) {
                case 0:
                    $values[$v] = $request->upper;
                    break;
                case 1:
                    $values[$v] = $request->middle;
                    break;
                case 2:
                    $values[$v] = $request->bottom;
                    break;
                case 3:
                    $values[$v] = $request->underTitle;
                    break;
                default:
                    $values[$v] = '';
                    break;
            }
        }

        switch ($mode) {
            case 'create':
                DB::table('m_user_pg_function')->insert($values + $where);
                break;
            case 'edit':
                DB::table('m_user_pg_function')->where($where)->update($values);
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'success' => true
        ]);
    }

    private function getConfWhereUserPgFunction()
    {
        return [
            'user_cd'   => Auth::id(),
            'pg_nm'     => 'sagyosiji',
            'function'  => 'comment'
        ];
    }

    private function prepareDataGroupBy($data, $colGroupByInjiGroup, $colGroupByTyohyo, $colGroupByOtherPrinter, $tyohyokbn)
    {
        $result = $data->groupBy(function ($item, $key) use($colGroupByInjiGroup, $colGroupByTyohyo, $colGroupByOtherPrinter){
            $colsGroupBy = [
                $item->$colGroupByInjiGroup,
                $item->$colGroupByTyohyo,
            ];
            if ($colGroupByOtherPrinter == 'syaban') {
                $colsGroupBy[] = $item->$colGroupByOtherPrinter;
            }
            return implode('__', $colsGroupBy);
        })->map(function ($group) use($tyohyokbn){
            return $group->map(function ($item) use($tyohyokbn) {
                $item['tyohyokbn'] = $tyohyokbn;
                return $item;
            });
        });

        return $result;
    }
}
