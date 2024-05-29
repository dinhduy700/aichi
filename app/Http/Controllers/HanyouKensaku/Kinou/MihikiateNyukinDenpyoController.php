<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController;
use App\Http\Repositories\HanyouKensaku\MihikiateNyukinDenpyoRepository;
use Illuminate\Http\Request;

class MihikiateNyukinDenpyoController extends HanyouKensakuController
{
    public function __construct(MihikiateNyukinDenpyoRepository $repository)
    {
        $this->dataRepository = $repository;
        $this->fileName = 'mihikiate_nyukin_denpyo.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/mihikiate_nyukin_denpyo.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/mihikiate_nyukin_denpyo.xlsx');
    }
}
