<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\MNinusi;
use DB;

class YugidaiNinusicdSearchRepository 
{
    public function getListWithTotalCount($request)
    {
        $maxNinusiCd = MNinusi::select(DB::raw('MAX(ninusi_cd) AS max_ninusi_cd'))
                        ->whereRaw('CHAR_LENGTH(CAST(ninusi_cd AS TEXT)) = 4')
                        ->first();
        $rows = MNinusi::select('ninusi_cd')
                ->where('ninusi_cd', $maxNinusiCd->max_ninusi_cd);
                
        return [
            'total' => $rows->count(),
            'rows' =>  $rows
        ];
    }
}