<?php

namespace App\Http\Controllers\Picking;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsPickingGurisuto;
use App\Helpers\Formatter;
use App\Http\Repositories\Picking\PickingGurisutoRepository;
use App\Http\Requests\Picking\PickingGurisutoRequest;

class PickingGurisutoExportController extends Controller
{
    protected $pickingGurisutoRepository;

    public function __construct(PickingGurisutoRepository $pickingGurisutoRepository)
    {
        $this->pickingGurisutoRepository = $pickingGurisutoRepository;
    }

    public function filterForm()
    {
        $printOtherOpts = $this->pickingGurisutoRepository->getExportPrintOtherOpts();

        return view('picking.picking-gurisuto.exp-filter-form', compact('printOtherOpts'));
    }

    public function filterValidate(PickingGurisutoRequest $request)
    {
        return responseSendForward($this->pickingGurisutoRepository->applyRequestToBuilder($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_nyusyuko.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $fileNm);
        return response()->download($savePath, 'ピッキングリスト_'.  date('YmdHis') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_nyusyuko.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = 'ピッキングリスト_'. date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $repo                   = $this->pickingGurisutoRepository;
        $exporter               = new XlsPickingGurisuto();
        $qb                     = $repo->applyRequestToBuilder($request);
        $data                   = $qb->get();
        
        $config = require(app_path('Helpers/Excel/config/t_nyusyuko_picking_gurisuto.php'));

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }

        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $template = 't_nyusyuko__picking_gurisuto.xlsx';

        $isSyaban = in_array(
            $repo::EXP_PRINT_OTHER_SYABAN,
            data_get($request, 'exp.print_other', [])
        );

        $isHachaku = in_array(
            $repo::EXP_PRINT_OTHER_HACHAKU_CD,
            data_get($request, 'exp.print_other', [])
        );

        data_set($config, 'is_syaban', $isSyaban);
        data_set($config, 'is_hachaku', $isHachaku);

        $exporter->export(
            app_path('Helpers/Excel/template/' . $template),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    public function csv(Request $request)
    {
        $repo = $this->pickingGurisutoRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_nyusyuko_picking_gurisuto.php'));
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
                'kisan_dt'
                //'add_dt', 'upd_dt'
            ];
            foreach ($dateKeys as $key) {
                $expRow[$key] = Formatter::date($row[$key]);
            }
           
            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, 'ピッキングリスト_'.  date('YmdHis').'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }
}
