<?php

namespace App\Http\Controllers\Tanaorosi;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsTanaorosi;
use App\Helpers\Formatter;
use App\Http\Repositories\Tanaorosi\TanaorosiRepository;
use App\Http\Requests\Tanaorosi\TanaorosiRequest;

class TanaorosiController extends Controller
{
    protected $tanaorosiRepository;

    public function __construct(TanaorosiRepository $tanaorosiRepository)
    {
        $this->tanaorosiRepository = $tanaorosiRepository;
    }

    public function filterForm()
    {
        $printOtherOpts = $this->tanaorosiRepository->getExportPrintOtherOpts();


        return view('tanaorosi.exp-filter-form', compact('printOtherOpts'));
    }

    public function filterValidate(TanaorosiRequest $request)
    {
        return responseSendForward($this->tanaorosiRepository->applyRequestToBuilder($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_zaiko.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        return response()->download($savePath, '棚卸記入表_'.  date('YmdHis') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_zaiko.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = '棚卸記入表_'. date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $routeNm    = $request->route()->getName();
        $repo       = $this->tanaorosiRepository;
        $exporter   = new XlsTanaorosi();

        $qb         = $repo->applyRequestToBuilder($request);
        $data       = $qb->get();

        $config = require(app_path('Helpers/Excel/config/t_zaiko_tanaorosi.php'));

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }

        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_zaiko_tanaorosi.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    public function csv(Request $request)
    {
        $repo = $this->tanaorosiRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_zaiko_tanaorosi.php'));
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

            $dateKeys = [
                'current_date',
            ];

            foreach ($dateKeys as $key) {
                $expRow[$key] = Formatter::date($row[$key]);
            }

            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, '棚卸記入表_'.  date('YmdHis').'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }
}
