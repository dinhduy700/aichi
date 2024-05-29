<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\NichibetsuUriageKingakuRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class NichibetsuUriageKingakuController extends Controller
{
    function __construct(NichibetsuUriageKingakuRepository $nichibetsuUriageKingakuRepository) {
        $this->dataRepository = $nichibetsuUriageKingakuRepository;
        $this->fileName = 'nichibetsu_uriage_kingaku.xlsx';
        $this->configExcel = require(app_path('Helpers/Excel/config/hanyou_kensaku/nichibetsu_uriage_kingaku.php'));
        $this->templatePath = app_path('Helpers/Excel/template/hanyou_kensaku/nichibetsu_uriage_kingaku.xlsx');
    }
}
