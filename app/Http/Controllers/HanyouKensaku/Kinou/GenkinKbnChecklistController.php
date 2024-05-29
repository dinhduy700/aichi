<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\GenkinKbnChecklistRepository;

class GenkinKbnChecklistController extends Controller
{
    function __construct(GenkinKbnChecklistRepository $genkinKbnChecklistRepository) {
        $this->dataRepository = $genkinKbnChecklistRepository;
        $this->fileName = 'genkin_kbn_checklist.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/genkin_kbn_checklist.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/genkin_kbn_checklist.xlsx');
    }
}
