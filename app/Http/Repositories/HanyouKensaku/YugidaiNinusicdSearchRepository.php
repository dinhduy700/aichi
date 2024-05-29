<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\MNinusi;
use DB;

class YugidaiNinusicdSearchRepository 
{
    public function getListWithTotalCount($request)
    {
        $maxNinusiCd = MNinusi::select(DB::raw('MAX(CAST(ninusi_cd AS INTEGER)) AS max_ninusi_cd'), 'ninusi_cd')
                ->whereRaw('LENGTH(ninusi_cd) = 4')->groupBy('ninusi_cd')->orderBy('max_ninusi_cd', 'DESC')->first();
        return [
            'total' => 1,
            'rows' =>  MNinusi::select('ninusi_cd')
                        ->where('ninusi_cd', $maxNinusiCd->ninusi_cd)
        ];
    }
}