<?php

namespace App\Http\Controllers\Seikyu;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Helpers\Csv\SeikyuCsvExport;
use App\Helpers\Excel\XlsSeikyusyo;
use App\Helpers\Pdf\PdfSeikyusyo;
use App\Http\Repositories\Seikyu\SeikyuRepository;
use App\Http\Requests\Seikyu\SeikyuRequest;

class SeikyuExportController extends Controller
{
    protected $seikyuRepository;

    public function __construct(SeikyuRepository $seikyuRepository)
    {
        $this->seikyuRepository = $seikyuRepository;
    }

    public function index(Request $request)
    {
        $setting            = require(app_path('Helpers/Grid/config/t_uriage_seikyu.php'));
        $midasisiteiOpts    = $this->seikyuRepository->getExportMidasisiteiOpts();
        $seikyusskOpts      = $this->seikyuRepository->getExportSeikyusskOpts();
        $printOtherOpts     = $this->seikyuRepository->getExportPrintOtherOpts();
        $maxDate            = $this->seikyuRepository->getMaxSeikyuSimeDt();

        return view('seikyu.seikyu-sho.index', compact('setting', 'request', 'midasisiteiOpts', 'seikyusskOpts', 'printOtherOpts', 'maxDate'));

    }

    public function dataList(Request $request)
    {
        $page       = $request->page ?? 1;
        $perPage    = config('params.PAGE_SIZE');

        $listData   = $this->seikyuRepository->getListWithTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']->get();

        return response()->json($data);
    }

    public function filterForm()
    {
        $midasisiteiOpts    = $this->seikyuRepository->getExportMidasisiteiOpts();
        $seikyusskOpts      = $this->seikyuRepository->getExportSeikyusskOpts();
        $printOtherOpts     = $this->seikyuRepository->getExportPrintOtherOpts();

        return view('seikyu.exp-filter-form', compact(
            'midasisiteiOpts', 'seikyusskOpts', 'printOtherOpts'
        ));
    }

    public function filterValidate(SeikyuRequest $request)
    {
        return response()->json([]);
    }

    public function excel(Request $request)
    {
        $outDir     = storage_path('app/download');
        $fileNm     = date('YmdHis') . '_t_uriage.xlsx';
        list($fileName, $savePath)   = $this->exportExcel($request, $outDir, $fileNm);

        return response()->download($savePath, '請求書_'.  date('YmdHis') . '.xlsx')
                ->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $qb         = $this->seikyuRepository->applyRequestToBuilder($request);
        $data       = $qb->get();
        $pdf        = new PdfSeikyusyo();
        
        $listZaikoHokanryoByNinusiCd = $this->seikyuRepository->getSumKinZaikoHokanryo($request)
                                ->get()->keyBy('ninusi_cd');

        $newFileName = $pdf->export($data, $listZaikoHokanryoByNinusiCd);

        return response()->json([
            'path' => route('seikyu.seikyu_sho.exp.previewPdf', ['file_name' => $newFileName]),
        ]);
    }

    public function previewPdf()
    {
        $fileName   = request('file_name');
        $pdfPath    = storage_path('app' . DIRECTORY_SEPARATOR . 'download' . DIRECTORY_SEPARATOR . $fileName);
        
        if (file_exists($pdfPath)) {
            return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
        } 
        
    }

    public function downloadPdf(Request $request)
    {
        $outDir     = storage_path('app/download');

        $qb         = $this->seikyuRepository->applyRequestToBuilder($request);
        $data       = $qb->get();
        $pdf        = new PdfSeikyusyo();

        $listZaikoHokanryoByNinusiCd = $this->seikyuRepository->getSumKinZaikoHokanryo($request)
                                ->get()->keyBy('ninusi_cd');

        $newFileName = $pdf->export($data, $listZaikoHokanryoByNinusiCd);

        $pdfPath = $outDir . DIRECTORY_SEPARATOR . $newFileName;

        return response()->download($pdfPath, $newFileName)->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $fileName)
    {
        $exporter   = new XlsSeikyusyo();

        $qb         = $this->seikyuRepository->applyRequestToBuilder($request);
        $data       = $qb->get();

        $listZaikoHokanryoByNinusiCd = $this->seikyuRepository->getSumKinZaikoHokanryo($request)
                                                                ->get()->keyBy('ninusi_cd');

        $config     = require(app_path('Helpers/Excel/config/t_uriage_seikyu.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $savePath = $directoryPath . DIRECTORY_SEPARATOR . $fileName;

        //見出指定
        $config['midasisitei'] = data_get($request, 'exp.midasisitei') == 3 ? 2 : 1;

        $template = 't_uriage_seikyu_f.xlsx';

        $config['listZaikoHokanryoByNinusiCd'] = $listZaikoHokanryoByNinusiCd;

        $exporter->export(
            app_path('Helpers/Excel/template/' . $template),//template
            $config,
            $data,
            $savePath
        );
        return [$fileName, $savePath];
    }

    public function csv(Request $request)
    {
        $repo       = $this->seikyuRepository;
        $savePath   = storage_path(date('YmdHis') . '.csv');
        $exp        = new SeikyuCsvExport();

        $exp->fp    = fopen($savePath, 'w');

        $mapping    = require(app_path('Helpers/Csv/config/t_uriage_seikyu.php'));
        $exportHeader = in_array(
            $repo::EXP_PRINT_OTHER_CSV_HEADER,
            data_get($request, 'exp.print_other', [])
        );

        if ($exportHeader) {
            $exp->fputcsv($mapping['header']);
            $exp->fputcsv($mapping['detail']);
        }

        $exp->exportData(function ($row, $mapping) {
            return null;
        });

        $exp->fclose();

        return response()->download($savePath, '請求書_'.  date('YmdHis').'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }

    public function updateTSeikyu(Request $request)
    {
        $this->seikyuRepository->updateTSeikyu($request->listNinusiCd, $request->seikyuSimeDt);

        return response()->json([
            'success' => true,
        ]);
        
    }
}
