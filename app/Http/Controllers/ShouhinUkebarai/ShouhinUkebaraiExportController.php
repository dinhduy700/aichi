<?php

namespace App\Http\Controllers\ShouhinUkebarai;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsShoukaiUkebarai;
use App\Http\Repositories\ShouhinUkebaraiRepository;
use App\Http\Requests\UketsukeHaraichoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ShouhinUkebaraiExportController
{
    protected $shouhinUkebaraiRepository;

    public function __construct(
        ShouhinUkebaraiRepository $shouhinUkebaraiRepository
    )
    {
        $this->shouhinUkebaraiRepository = $shouhinUkebaraiRepository;
    }

    public function filterForm()
    {
        $optionOpts = $this->shouhinUkebaraiRepository->getOptionOpts();
        return view('shouhin_ukebarai.exp-filter-form', compact('optionOpts'));
    }

    public function filterValidate(UketsukeHaraichoRequest $request)
    {
        return responseSendForward($this->shouhinUkebaraiRepository->applyRequestToBuilder($request));
    }

    public function shouhinUkebaraiExcel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_' . getPageTitle('shouhin_ukebarai.exp.filterForm') . '.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        return response()->download($savePath, time() . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function exportExcel($request, $outDir, $filename)
    {
        $exporter = new XlsShoukaiUkebarai();
        $qb = $this->shouhinUkebaraiRepository->applyRequestToBuilder($request);
        $groupedResults = [];
        $config = require(app_path('Helpers/Excel/config/shouhin_ukebarai.php'));
        if (!in_array($this->shouhinUkebaraiRepository::EXP_PRINT_GROUP, data_get($request->all(), 'exp.option', []))) {
            $config['base']['header']['others']['showLot']['value'] = '規格';
            unset($config['block'][2]['J']);
        } else {
            $config['base']['header']['others']['showLot']['value'] = '規格・ロット';
        }
        if ($qb->count() > 0) {
            $results = $qb->get()->toArray();
            $groupBy = [
                'bumon_cd',
                'ninusi_cd',
            ];
            $groupedResults = collect($results)->groupBy(function ($item) use ($groupBy) {
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
            app_path('Helpers/Excel/template/shouhin_ukebarai.xlsx'),//template
            $config,
            $groupedResults,
            $savePath
        );
        return $savePath;
    }

    public function shouhinUkebaraiPdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_' . getPageTitle('shouhin_ukebarai.exp.filterForm') . '.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR . str_replace('.xlsx', '.pdf', $fileNm);
        cnvXlsToPdf($savePath, $outDir);
        if (File::exists($savePath)) {
            File::delete($savePath);
        }
        $fileName = date('YmdHis') . '_' . getPageTitle('shouhin_ukebarai.exp.filterForm') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])->deleteFileAfterSend(true);

    }

    public function shouhinUkebaraiCsv(Request $request)
    {
        $repo = $this->shouhinUkebaraiRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/shouhin_ukebarai.php'));
        $qb = $repo->applyRequestToBuilder($request);
        $options = [
            'kisan_dt_from' => data_get($request->all(), 'exp.kisan_dt_from', null),
            'kisan_dt_to' => data_get($request->all(), 'exp.kisan_dt_to', null)
        ];
        $exp->qb = $qb;
        $exportHeader = in_array(
            $repo::EXP_PRINT_CSV_HEADER,
            data_get($request->all(), 'exp.option', [])
        );
        if ($exportHeader) $exp->fputcsv($mapping);
        $exp->exportData(function ($row) use ($options, $mapping) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }
            $expRow['kisan_dt_from'] = data_get($options, 'kisan_dt_from');
            $expRow['kisan_dt_to'] = data_get($options, 'kisan_dt_to');
            return $expRow;
        });

        $exp->fclose();

        return response()->download(
            $savePath,
            getPageTitle('shouhin_ukebarai.exp.filterForm') . '_' . date('YmdHis') . '.csv',
            ['Content-Type: text/csv']
        )->deleteFileAfterSend(true);
    }
}