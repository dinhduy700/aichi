<?php

namespace App\Http\Controllers\Nyukin;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsNyukin;
use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NyukinRequest;
use App\Http\Repositories\NyukinRepository;
use Illuminate\Support\Facades\File;

class NyukinExportController extends Controller
{
    protected $nyukinRepository;

    public function __construct(NyukinRepository $nyukinRepository)
    {
        $this->nyukinRepository = $nyukinRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function filterForm()
    {
        return view('nyukin.nyukin-export', []);
    }

    public function filterValidate(NyukinRequest $request)
    {
        return responseSendForward($this->nyukinRepository->applyRequestToBuilder($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_nyukin.xlsx';
        return response()->download(
            $this->exportExcel($request, $outDir, $fileNm),
            getPageTitle('nyukin.exp.filterForm') . '_' . date('YmdHis') . '.xlsx'
        )->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_nyukin.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = getPageTitle('nyukin.exp.filterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $exporter = new XlsNyukin();
        $qb = $this->nyukinRepository->applyRequestToBuilder($request);
        $data = $qb->get();
        $config = require(app_path('Helpers/Excel/config/t_nyukin.php'));

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_nyukin.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    public function csv(Request $request)
    {
        $repo = $this->nyukinRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_nyukin.php'));

        $exp->qb = $repo->applyRequestToBuilder($request);

        $options = [];

        $isHeader = $request->input('exp.header');
        if($isHeader == 1) {
            $exp->fputcsv($mapping);//export header
        }

        $exp->exportData(function ($row) use ($options, $mapping) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }

            $dateKeys = [
                'nyukin_dt','seikyu_sime_dt','tegata_kijitu_kin','hikiate_simebi_dt',
                //'add_dt', 'upd_dt'
            ];
            foreach ($dateKeys as $key) {
                $expRow[$key] = Formatter::date($row[$key]);
            }
            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, getPageTitle('nyukin.exp.filterForm').'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }
}
