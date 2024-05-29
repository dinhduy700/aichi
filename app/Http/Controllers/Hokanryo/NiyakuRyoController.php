<?php

namespace App\Http\Controllers\Hokanryo;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsHokanryoNiyakuryo;
use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Hokanryo\NiyakuRyoRepository;
use App\Http\Requests\Hokanryo\NiyakuRyoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NiyakuRyoController extends Controller
{
    protected $repository;

    public function __construct(NiyakuRyoRepository $repository)
    {
        $this->repository = $repository;
    }

    // 23. 荷役料・荷役料請求計算書
    public function filterForm()
    {
        $optionOpts = $this->repository->getOptionOpts();
        $printOpts = $this->repository->getPrintOpts();
        $initValues = [];
        return view('hokanryo.niyaku-ryo.exp-filter-form', compact(
            'optionOpts', 'printOpts', 'initValues'
        ));
    }

    public function filterValidate(NiyakuRyoRequest $request)
    {
        return responseSendForward($this->repository->applyRequestToBuilder($request));
    }

    public function csv(Request $request)
    {
        $repo = $this->repository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_hokanryo_niyakuryo.php'));

        $exp->qb = $repo->applyRequestToBuilder($request);

        $options = [
            'hakko_dt' => data_get($request->all(), 'exp.hakko_dt', null)
        ];

        if ($request->filled('exp.option') && in_array($repo::EXP_PRINT_CSV_HEADER, $request->input('exp.option'))) {
            $exp->fputcsv($mapping);
        }

        $exp->exportData(function ($row) use ($options, $mapping, $request) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                switch ($k) {
                    case 'hakko_dt': $expRow[$k] = Formatter::date(data_get($options, 'hakko_dt')); break;
                    case 'seikyu_sime_dt': $expRow[$k] = Formatter::date(data_get($row, $k)); break;
                    case '会社名': $expRow[$k] = '有限会社　愛知高速運輸'; break;
                    case '倉庫請求書印字分母': $expRow[$k] = 1; break;
                    default: $expRow[$k] = data_get($row, $k, '');
                }

            }
            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, getPageTitle('hokanryo.niyakuryo.filterForm') . '_' . date('YmdHis') . '.csv', ['Content-Type: text/cvs'])
            ->deleteFileAfterSend(true);
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $filename = date('YmdHis') . '_hokanryo_niyakuryo.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $filename);
        return response()->download($savePath, getPageTitle('hokanryo.niyakuryo.filterForm')  . '_' . date('YmdHis') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $filename = date('YmdHis') . '_hokanryo_niyakuryo.xlsx';
        $savePath = $this->exportExcel($request, $outDir, $filename);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR . str_replace('.xlsx', '.pdf', $filename);
        cnvXlsToPdf($savePath, $outDir);
        if (File::exists($savePath)) {
            File::delete($savePath);
        }
        $fileName = getPageTitle('hokanryo.niyakuryo.filterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        $exporter = new XlsHokanryoNiyakuryo();
        $repo = $this->repository;
        $data = $repo->applyRequestToBuilder($request)->get();

        $config = require(app_path('Helpers/Excel/config/t_hokanryo_niyakuryo.php'));

        $config['base']['header']['others']['hakko_dt']['value'] = data_get($request->all(), 'exp.hakko_dt');
        if (in_array($repo::EXP_HIDE_EXP_DT, data_get($request->all(), 'exp.option', []))) {
            $fnc = $config['EXP_HIDE_EXP_DT'];
            $fnc($config);
        }
        if ($repo::EXP_KBN_SEIKYU == data_get($request->all(), 'exp.export_kbn')) {
            $fnc = $config['EXP_KBN_SEIKYU'];
            $fnc($config);
        }

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_hokanryo_niyakuryo.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }
}
