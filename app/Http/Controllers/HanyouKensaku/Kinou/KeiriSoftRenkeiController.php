<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\KeiriSoftRenkeiRepository;
use App\Helpers\CsvExport;
class KeiriSoftRenkeiController extends Controller
{
    function __construct(KeiriSoftRenkeiRepository $keiriSoftRenkeiRepository) {
        $this->dataRepository = $keiriSoftRenkeiRepository;
        $this->fileName = 'keiri_soft_renkei.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/keiri_soft_renkei.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/keiri_soft_renkei.xlsx');
    }

    public function exportCsv(Request $request) 
    {
        $repo = $this->dataRepository;

        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/keiri_soft_renkei.php'));
        $exp->qb = $repo->getListWithTotalCount($request)['rows'];
        $options = [];
        $exp->fputcsv(['経理ソフト連携']);
        $from = '運送日FROM:';
        $to = '運送日TO:';
        if (!empty($request->field)) {
            $arrDate = [];
            foreach ($request->field as $key => $value) {
                if ($value == 'unso_dt'  && $request->value[$key]) {
                    $arrDate[] = $request->value[$key];
                }
            }
            if(!empty($arrDate)) {
                $from .= min($arrDate);
                if(count($arrDate) >= 2) {
                    $to .= max($arrDate);
                }
            }
        }

        $exp->fputcsv([$from. '　'. $to]);
        $exp->fputcsv([]);
        $exp->fputcsv($mapping);
        $exp->exportData(function ($row) use ($options, $mapping, $request) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }
            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, '経理ソフト連携'.'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }
}
