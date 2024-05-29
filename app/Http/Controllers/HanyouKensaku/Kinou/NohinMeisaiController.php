<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Helpers\Excel\XlsNohinMeisai;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HanyouKensaku\HanyouKensakuController;
use App\Http\Repositories\HanyouKensaku\NohinMeisaiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class NohinMeisaiController extends HanyouKensakuController
{
    public function __construct(NohinMeisaiRepository $repository)
    {
        $this->dataRepository = $repository;
        $this->fileName = 'nohin_meisai.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/nohin_meisai.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/nohin_meisai.xlsx');
        $this->exporter = new XlsNohinMeisai();
    }
}
