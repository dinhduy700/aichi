<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\IdoharigamiRepository;
use App\Helpers\Excel\XlsIdoharigami;

class IdoharigamiController extends Controller
{
    function __construct(IdoharigamiRepository $idoharigamiRepository) {
        $this->dataRepository = $idoharigamiRepository;
        $this->fileName = 'genkin_kbn_checklist.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/idoharigami.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/idoharigami.xlsx');
    }

    public function exportExcelDataTable(Request $request) {
        $exporter = new XlsIdoharigami();
        $qb = $this->dataRepository->getListWithTotalCount($request)['rows'];
        $data = $qb->get();
        $outDir = storage_path('app/download');
        $filename =  date('YmdHis') . 'idoharigami.xlsx';
        $config = $this->configExcel;

        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            $this->templatePath,//template
            $config,
            $data,
            $savePath
        );

        return response()->download($savePath, 'idoharigami.xlsx')
            ->deleteFileAfterSend(true);
    }
}
