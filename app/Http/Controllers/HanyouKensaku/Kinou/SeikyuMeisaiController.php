<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\SeikyuMeisaiRepository;
use App\Helpers\Excel\XlsSeikyuMeisai;

class SeikyuMeisaiController extends Controller
{
    function __construct(SeikyuMeisaiRepository $seikyuMeisaiRepository) {
        $this->dataRepository = $seikyuMeisaiRepository;
        $this->fileName = 'seikyu_meisai.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/seikyu_meisai.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/seikyu_meisai.xlsx');
    }

    public function exportExcelDataTable(Request $request) {
    	$exporter = new XlsSeikyuMeisai();
        $qb = $this->dataRepository->getListWithTotalCount($request)['rows'];
        $data = $qb->get();
        $outDir = storage_path('app/download');
        $filename =  date('YmdHis') . 'seikyu_meisai.xlsx';
        $config = $this->configExcel;

        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            $this->templatePath,//template
            $config,
            $data,
            $savePath
        );

        return response()->download($savePath, 'seikyu_meisai.xlsx')
            ->deleteFileAfterSend(true);
    }


}
