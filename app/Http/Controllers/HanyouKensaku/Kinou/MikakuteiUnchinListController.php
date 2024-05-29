<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController;
use App\Http\Repositories\HanyouKensaku\MikakuteiUnchinListRepository;
use Illuminate\Http\Request;

class MikakuteiUnchinListController extends HanyouKensakuController
{
    public function __construct(MikakuteiUnchinListRepository $repository)
    {
        $this->dataRepository = $repository;
        $this->fileName = 'mikakutei_unchin_list.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/mikakutei_unchin_list.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/mikakutei_unchin_list.xlsx');
    }
}
