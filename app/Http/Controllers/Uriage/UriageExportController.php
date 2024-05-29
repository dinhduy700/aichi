<?php

namespace App\Http\Controllers\Uriage;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsUriageList;
use App\Helpers\Formatter;
use App\Helpers\XlsExportMstMultiRowBlock;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UriageRepository;
use App\Http\Requests\UriageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UriageExportController extends Controller
{
    protected $uriageRepository;

    public function __construct(
        UriageRepository $uriageRepository
    ) {
        $this->uriageRepository = $uriageRepository;
    }

    public function filterForm(Request $request)
    {
        $orderByOpts = $this->uriageRepository->getExportOrderByOpts();
        $initValue = [];
        if ($request->method() === "POST") {
            $initValue = $request->except('_token');
        }
        return view('uriage.exp-filter-form', compact(
            'orderByOpts', 'initValue'
        ));
    }

    public function filterValidate(UriageRequest $request)
    {
        $request = new Request($request->exp ?? []);
        return responseSendForward($this->uriageRepository->qbExport($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_uriage.xlsx';
        return response()->download(
            $this->exportExcel($request, $outDir, $fileNm),
            getPageTitle('uriage.exp.filterForm') . '_' . date('YmdHis') . '.xlsx'
        )->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_uriage.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = getPageTitle('uriage.exp.filterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $request = new Request($request->exp ?? []);
        $exporter = new XlsUriageList();
        $data = $this->uriageRepository->qbExport($request)->get();

        $config = require(app_path('Helpers/Excel/config/t_uriage.php'));

        // paging
        $pages = [];
        $iBumon = null;
        $pagesize = $config['base']['template']['page']['size'];
        $page = [];
        $cnt = 0;
        foreach ($data as $i => $row) {
            if (data_get($row, 'bumon_cd') != $iBumon || $cnt == $pagesize) {
                if (!empty($page)) array_push($pages, $page);
                $page = [];
                $cnt = 0;
            }
            $iBumon = data_get($row, 'bumon_cd');
            array_push($page, $row);
            $cnt++;
        }
        if (!empty($page)) array_push($pages, $page);

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;
        $exporter->export(
            app_path('Helpers/Excel/template/t_uriage.xlsx'),//template
            $config,
            $pages,
            $savePath
        );
        return $savePath;
    }

    public function csv(Request $request)
    {
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_uriage.php'));
        $routeNm = $request->route()->getName();
        $request = new Request($request->exp ?? []);
        $qb = $this->uriageRepository->qbExport($request, true, $routeNm);
        $exp->qb = $qb;

        $exp->fputcsv($mapping);//export header

        $options = [
            'jiyo_kbn' => configParam('options.m_syaryo.jiyo_kbn')
        ];

        $exp->exportData(function ($row) use ($mapping, $options) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }
            $expRow['jiyo_kbn'] = data_get($options, 'jiyo_kbn.' . $expRow['jiyo_kbn'], $expRow['jiyo_kbn']);
            $dateKeys = [
                'unso_dt', 'seikyu_keijyo_dt', 'seikyu_sime_dt',
                'yousya_sime_dt', 'yousya_keijyo_dt', 'nipou_dt',
            ];
            foreach ($dateKeys as $key) {
                $expRow[$key] = Formatter::date($row[$key]);
            }
            return $expRow;
        });
        $exp->fclose();

        return response()->download(
            $savePath,
            getPageTitle('uriage.exp.filterForm') . '_' . date('YmdHis') . '.csv',
            ['Content-Type: text/csv']
        )->deleteFileAfterSend(true);
    }
}
