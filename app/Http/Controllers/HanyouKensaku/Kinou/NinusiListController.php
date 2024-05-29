<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\NinusilistRepository;

class NinusiListController extends Controller
{
    function __construct(NinusilistRepository $ninusilistRepository) {
        $this->dataRepository = $ninusilistRepository;
        $this->fileName = 'ninusi_list.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/ninusi_list.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/ninusi_list.xlsx');
    }
}
