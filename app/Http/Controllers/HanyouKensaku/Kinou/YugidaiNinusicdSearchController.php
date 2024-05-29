<?php

namespace App\Http\Controllers\HanyouKensaku\Kinou;

use App\Http\Controllers\HanyouKensaku\HanyouKensakuController as Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HanyouKensaku\YugidaiNinusicdSearchRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class YugidaiNinusicdSearchController extends Controller
{
    function __construct(YugidaiNinusicdSearchRepository $yugidaiNinusicdSearchRepository) {
        $this->dataRepository = $yugidaiNinusicdSearchRepository;
        $this->fileName = '';
        $this->configExcel = null;
        $this->templatePath = '';
    }
}
