<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\SeikyuzanKakuninRepository;

class SeikyuzanKakuninController extends Controller
{
    function __construct(SeikyuzanKakuninRepository $seikyuzanKakuninRepository) {
        $this->dataRepository = $seikyuzanKakuninRepository;
        $this->fileName = 'seikyuzan_kakunin.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/seikyuzan_kakunin.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/seikyuzan_kakunin.xlsx');
    }
}
