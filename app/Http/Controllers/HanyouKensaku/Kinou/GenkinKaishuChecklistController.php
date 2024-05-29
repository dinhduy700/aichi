<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\GenkinKaishuChecklistRepository;

class GenkinKaishuChecklistController extends Controller
{
    function __construct(GenkinKaishuChecklistRepository $genkinKaishuChecklistRepository) {
        $this->dataRepository = $genkinKaishuChecklistRepository;
        $this->fileName = 'genkin_kaishu_checklist.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/genkin_kaishu_checklist.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/genkin_kaishu_checklist.xlsx');
    }
}
