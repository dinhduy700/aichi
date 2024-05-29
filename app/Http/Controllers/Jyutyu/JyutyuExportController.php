<?php

namespace App\Http\Controllers\Jyutyu;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsJyutyuList;
use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Repositories\JyutyuRespository;
use App\Http\Requests\JyutyuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class JyutyuExportController extends Controller
{
    protected $jyutyuRepository;

    public function __construct(JyutyuRespository $jyutyuRepository)
    {
        $this->jyutyuRepository = $jyutyuRepository;
    }

    public function filterForm()
    {
        $injiGroupOpts = $this->jyutyuRepository->getExportInjiGroupOpts();
        $syuturyokuKbnOpts = $this->jyutyuRepository->getExportSyuturyokuKbnOpts();
        $printOtherOpts = $this->jyutyuRepository->getExportPrintOtherOpts();
        $printOrderFields = [
            'jyutyu_kbn' => '受注区分',
            'ninusi_cd' => '荷主',
            'hatuti_cd' => '発地',
            'hachaku_cd' => '着地',
        ];

        return view('jyutyu.exp-filter-form', compact(
            'injiGroupOpts', 'syuturyokuKbnOpts', 'printOrderFields', 'printOtherOpts'
        ));
    }

    public function filterValidate(JyutyuRequest $request)
    {
        return responseSendForward($this->jyutyuRepository->applyRequestToBuilder($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_jyutyu.xlsx';
        return response()->download(
            $this->exportExcel($request, $outDir, $fileNm),
            getPageTitle('jyutyu.exp.filterForm') . '_' . date('YmdHis') . '.xlsx'
        )->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_jyutyu.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = getPageTitle('jyutyu.exp.filterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $exporter = new XlsJyutyuList();
        $qb = $this->jyutyuRepository->applyRequestToBuilder($request);
        $data = $qb->get();

        $config = require(app_path('Helpers/Excel/config/t_uriage_jyutyu.php'));

        // inji_group
        $dateField = ["label" => "集荷日", "field" => 'syuka_dt'];
        if (data_get($request, 'exp.inji_group')=='haitatu_dt') {
            $dateField = ["label" => "配達日", "field" => 'haitatu_dt'];
        }
        $config['base']['groupBy'] = [$dateField['field'], 'bumon_cd'];
        array_push($config['base']['header']['others'], [
            'col' => 'A', 'row' => 4, 'value' => function ($page) use ($dateField) {
                return $dateField['label']
                    . ' '
                    . Formatter::dateJP(data_get($page, "0." . $dateField['field']), Formatter::DT_SHORT_JP_YYMMDD_W);
            }
        ]);

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_uriage_jyutyu.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    public function csv(Request $request)
    {
        $repo = $this->jyutyuRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_uriage_jyutyu.php'));

        $exp->qb = $repo->applyRequestToBuilder($request);

        $exportHeader = in_array(
            $repo::EXP_PRINT_OTHER_CSV_HEADER,
            data_get($request, 'exp.print_other', [])
        );
        if ($exportHeader) $exp->fputcsv($mapping);//export header

        $exp->exportData(function ($row) use ($mapping) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }
            $expRow['hinmei_nm'] = data_get($row, 'hinmoku_nm') . ' ' . data_get($row, 'hinmei_nm');
            $dateKeys = [
                'syuka_dt','haitatu_dt','unso_dt','seikyu_keijyo_dt','seikyu_sime_dt','yousya_sime_dt',
                'denpyo_send_dt','nipou_dt', 'kaisyu_dt',
            ];
            foreach ($dateKeys as $key) {
                $expRow[$key] = Formatter::date($row[$key]);
            }
            return $expRow;
        });
        $exp->fclose();

        return response()->download(
            $savePath,
            getPageTitle('jyutyu.exp.filterForm') . '_' . date('YmdHis') . '.csv',
            ['Content-Type: text/csv']
        )->deleteFileAfterSend(true);
    }
}
