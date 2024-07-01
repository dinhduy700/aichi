<?php

namespace App\Http\Controllers\UketsukeHaraicho;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsUketsukeHaraicho;
use App\Http\Repositories\UketsukeHaraichoRepository;
use App\Http\Requests\UketsukeHaraichoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class UketsukeHaraichoExportController
{
    protected $uketsukeHaraichoRepository;

    protected $configNyusyukoKbn;

    public function __construct(
        UketsukeHaraichoRepository $uketsukeHaraichoRepository
    )
    {
        $this->uketsukeHaraichoRepository = $uketsukeHaraichoRepository;
        $this->configNyusyukoKbn = configParam('NYUSYUKO_KBN_SUPPORT');
    }

    public function filterForm()
    {
        $optionOpts = $this->uketsukeHaraichoRepository->getOptionOpts();
        return view('uketsuke_haraicho.exp-filter-form', compact('optionOpts'));
    }

    public function filterValidate(UketsukeHaraichoRequest $request)
    {
        return responseSendForward($this->uketsukeHaraichoRepository->applyRequestToBuilder($request));
    }

    public function uketsukeHaraichoCsv(Request $request)
    {
        $repo = $this->uketsukeHaraichoRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/uketsuke_haraicho.php'));
        $qb = $repo->applyRequestToBuilder($request);
        $options = [
            'kisan_dt_from' => data_get($request->all(), 'exp.kisan_dt_from', null),
            'kisan_dt_to' => data_get($request->all(), 'exp.kisan_dt_to', null)
        ];
        $groupedResults = [];
        if ($qb->count() > 0) {
            $results = $qb->get()->toArray();
            $groupBy = [
                'bumon_cd',
                'ninusi_cd',
                'hinmei_cd'
            ];
            if (in_array($this->uketsukeHaraichoRepository::EXP_PRINT_GROUP, data_get($request->all(), 'exp.option', []))) {
                $groupBy = array_merge($groupBy, ['lot1', 'lot2', 'lot3']);
            }
            $groupedResults = collect($results)->groupBy(function ($item) use ($groupBy) {
                $groupKey = [];
                foreach ($groupBy as $field) {
                    $groupKey[] = $item->$field;
                }
                return implode('-', $groupKey);
            });
        }
        $exp->listGroup = $groupedResults;
        $exportHeader = in_array(
            $repo::EXP_PRINT_CSV_HEADER,
            data_get($request->all(), 'exp.option', [])
        );
        if ($exportHeader) $exp->fputcsv($mapping);
        $fieldGroup = array_slice($mapping, 0, 19) + array_slice($mapping, -4, 4);
        $exp->exportDataCollection(function ($rows, $key, $row) use ($options, $mapping) {
            $expRow = [];
            if (empty(data_get($row, 'nyusyuko_den_no'))) {
                return $expRow;
            }
            $arrConvertType = [
                'in_case_su',
                'in_hasu',
                'in_su',
                'in_jyuryo',
                'out_case_su',
                'out_hasu',
                'out_su',
                'out_jyuryo',
            ];
            foreach (array_keys($mapping) as $k) {
                if (in_array($k, $arrConvertType)) {
                    $expRow[$k] = round(data_get($row, $k, ''), 3);
                } else {
                    $expRow[$k] = data_get($row, $k, '');
                }
            }
            $arrReCalSu = $rows->filter(function ($item, $index) use ($key) {
                return $index <= $key;
            });
            $sumIn = $arrReCalSu->sum('in_su');
            $sumOut = $arrReCalSu->sum('out_su');
            $zaikoSu = data_get($row, 'zaiko_su') + $sumIn - $sumOut;
            $expRow['kisan_dt_from'] = data_get($options, 'kisan_dt_from');
            $expRow['kisan_dt_to'] = data_get($options, 'kisan_dt_to');
            $expRow['kisan_dt'] = Carbon::parse(data_get($row, 'kisan_dt'))->format('Y/m/d');
            $expRow['hidzuke_dt'] = Carbon::parse(data_get($row, 'hidzuke_dt'))->format('Y/m/d');
            $expRow['zaiko_case_su'] = floor($zaikoSu / data_get($row, 'irisu'));
            $expRow['zaiko_hasu'] = $zaikoSu % data_get($row, 'irisu');
            $expRow['zaiko_su'] = $zaikoSu;
            $expRow['zaiko_jyuryo'] = $zaikoSu * data_get($row, 'bara_tani_juryo');
            $expRow['nyusyuko_kbn'] = !empty($this->configNyusyukoKbn[$expRow['nyusyuko_kbn']]) ? $this->configNyusyukoKbn[$expRow['nyusyuko_kbn']] : '';
            return $expRow;
        }, function ($rows) use ($fieldGroup, $mapping, $options) {
            $rowGroup = [];
            foreach (array_keys($mapping) as $k) {
                if (in_array($k, array_keys($fieldGroup))) {
                    $rowGroup[$k] = data_get($rows->first(), $k, '');
                } else {
                    $rowGroup[$k] = "";
                }
            }
            $rowGroup['todokesaki_nm'] = "【繰 越】";
            $rowGroup['kisan_dt_from'] = data_get($options, 'kisan_dt_from');
            $rowGroup['kisan_dt_to'] = data_get($options, 'kisan_dt_to');
            $firstItem = $rows->first();
            $rowGroup['zaiko_case_su'] = round(data_get($firstItem, 'zaiko_case_su', 0), 3);
            $rowGroup['zaiko_hasu'] = round(data_get($firstItem, 'zaiko_hasu', 0) , 3);
            $rowGroup['zaiko_su'] = round(data_get($firstItem, 'zaiko_su', 0), 3);
            $rowGroup['zaiko_jyuryo'] = round(data_get($firstItem, 'zaiko_jyuryo', 0) ,3);
            return $rowGroup;
        });

        $exp->fclose();

        return response()->download(
            $savePath,
            getPageTitle('uketsuke_haraicho.exp.filterForm') . '_' . date('YmdHis') . '.csv',
            ['Content-Type: text/csv']
        )->deleteFileAfterSend(true);
    }

    public function exportExcel($request, $outDir, $filename)
    {
        $exporter = new XlsUketsukeHaraicho();
        $qb = $this->uketsukeHaraichoRepository->applyRequestToBuilder($request);
        $groupedResults = [];
        $config = require(app_path('Helpers/Excel/config/uketsuke_haraicho.php'));
        $groupBy = [
            'bumon_cd',
            'ninusi_cd',
            'hinmei_cd'
        ];
        if (!in_array($this->uketsukeHaraichoRepository::EXP_PRINT_GROUP, data_get($request->all(), 'exp.option', []))) {
            $config['base']['header']['others'] = array_slice($config['base']['header']['others'], 0, -3);
        } else {
            $groupBy = array_merge($groupBy, ['lot1', 'lot2', 'lot3']);
        }
        if ($qb->count() > 0) {
            $results = $qb->get()->toArray();
            $groupedResults = collect($results)->groupBy(function ($item) use($groupBy) {
                $groupKey = [];
                foreach ($groupBy as $field) {
                    $groupKey[] = $item->$field;
                }
                return implode('-', $groupKey);
            });
        }
        $directoryPath = $outDir;
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }
        $savePath = $directoryPath . DIRECTORY_SEPARATOR . $filename;
        $exporter->export(
            app_path('Helpers/Excel/template/uketsuke_haraicho.xlsx'),//template
            $config,
            $groupedResults,
            $savePath
        );
        return $savePath;

    }

    public function uketsukeHaraichoExcel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_' . getPageTitle('uketsuke_haraicho.exp.filterForm') . '.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        return response()->download($savePath, time() . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function uketsukeHaraichoPdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_' . getPageTitle('uketsuke_haraicho.exp.filterForm') . '.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR . str_replace('.xlsx', '.pdf', $fileNm);
        cnvXlsToPdf($savePath, $outDir);
        if (File::exists($savePath)) {
            File::delete($savePath);
        }
        $fileName = date('YmdHis') . '_' . getPageTitle('uketsuke_haraicho.exp.filterForm') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])->deleteFileAfterSend(true);

    }
}