<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\UntenGeppoRepository;

class UntenGeppoController extends Controller
{
    function __construct(UntenGeppoRepository $untengeppoRepository) {
        $this->dataRepository = $untengeppoRepository;
        $this->fileName = 'unten_geppo.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/unten_geppo.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/unten_geppo.xlsx');
    }
}
