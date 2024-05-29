<?php

namespace App\Http\Controllers\Seikyu;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsSeikyuList;
use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Seikyu\SeikyuListRepository;
use App\Http\Requests\Seikyu\SeikyuListRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SeikyuListController extends Controller
{
    protected $repository;
    public function __construct(SeikyuListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/seikyu/t_seikyu_list.php'));
        return view('seikyu.list.index', ['setting' => $setting, 'request' => $request]);
    }

    public function dataList(SeikyuListRequest $request)
    {
        $listData = $this->repository->getList($request);
        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->orderBy('ninusi_cd')
            ->get();

        return response()->json($data);
    }

    public function filterForm(Request $request)
    {
        $optionOpts = $this->repository->getOptionOpts();
        $initValues = [];
        $selectedItems = data_get($request->all(), 'list.selected', []);
        return view('seikyu.list.exp-filter-form', compact('optionOpts', 'initValues', 'selectedItems'));
    }

    public function filterValidate(SeikyuListRequest $request)
    {
        return responseSendForward($this->repository->applyRequestToBuilder($request));
    }

    public function  excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_seikyu_list.xlsx';
        return response()->download(
            $this->exportExcel($request, $outDir, $fileNm),
            getPageTitle('seikyu.list.filterForm') . '_' . date('YmdHis') . '.xlsx'
        )->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_seikyu_list.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = getPageTitle('seikyu.list.filterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $exporter = new XlsSeikyuList();
        $qb = $this->repository->applyRequestToBuilder($request);
        $data = $qb->get();

        $config = require(app_path('Helpers/Excel/config/t_seikyu_list.php'));

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_seikyu_list.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    public function  csv(Request $request)
    {
        $repo = $this->repository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_seikyu_list.php'));
        $qb = $repo->applyRequestToBuilder($request);;
        $exp->qb = $qb;

        $exportHeader = in_array(
            $repo::EXP_PRINT_CSV_HEADER,
            data_get($request->all(), 'exp.option', [])
        );
        if ($exportHeader) $exp->fputcsv($mapping);//export header

        $exp->exportData(function ($row) use ($mapping) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                switch ($k) {
                    case 'seikyu_sime_dt':
                        $expRow[$k] = Formatter::date($row[$k]);
                        break;
                    case 'kaisyu_yotei':
                        $expRow[$k] = SeikyuListRepository::getKaisyuYoteiDt(
                            data_get($row, 'seikyu_sime_dt'), data_get($row, 'kaisyu1_dd'), data_get($row, 'kaisyu2_dd')
                        )->format(Formatter::DF_DATE);
                        break;
                    default:
                        $expRow[$k] = data_get($row, $k, '');
                }
            }
            return $expRow;
        });
        $exp->fclose();

        return response()->download(
            $savePath,
            getPageTitle('seikyu.list.filterForm') . '_' . date('YmdHis') . '.csv',
            ['Content-Type: text/csv']
        )->deleteFileAfterSend(true);
    }
}
