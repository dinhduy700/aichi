<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\SuitochoNyuryokuListRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SuitochoNyuryokuListController extends Controller
{
    function __construct(SuitochoNyuryokuListRepository $suitochoNyuryokuList) {
        $this->dataRepository = $suitochoNyuryokuList;
        $this->fileName = 'suitocho_nyuryoku_list.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/suitocho_nyuryoku_list.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/suitocho_nyuryoku_list.xlsx');
    }
}
