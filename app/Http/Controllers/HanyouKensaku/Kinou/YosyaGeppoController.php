<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\YosyaRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class YosyaGeppoController extends Controller
{
    function __construct(YosyaRepository $yosysGeppoRepository) {
        $this->dataRepository = $yosysGeppoRepository;
        $this->fileName = 'yosya_geppo.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/yosya_geppo.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/yosya_geppo.xlsx');
    }
}
