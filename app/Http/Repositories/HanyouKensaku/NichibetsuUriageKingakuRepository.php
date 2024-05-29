<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class NichibetsuUriageKingakuRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.ninusi_cd',
            'm_ninusi.ninusi1_nm',
            't_uriage.haitatu_dt',
            DB::raw('SUM(t_uriage.unchin_kin) as unchin_kin'),
            DB::raw('SUM(t_uriage.tyukei_kin) as tyukei_kin'),
            DB::raw('SUM(t_uriage.syuka_kin) as syuka_kin'),
            DB::raw('SUM(t_uriage.tesuryo_kin) as tesuryo_kin'),
            DB::raw('SUM(t_uriage.unten_kin) as unten_kin')
        );

        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd');
 
        if(!empty($request->field)) {
            foreach($request->field as $key => $value) {
                if($value) {
                    if(!empty($request->logical_operator[$key -1])) {
                        if(strtolower($request->logical_operator[$key -1]) == 'and') {
                            $qb->where("t_uriage.".$value, $request->operator[$key], $request->value[$key]);
                        } else {
                            $qb->orWhere("t_uriage.".$value, $request->operator[$key], $request->value[$key]);
                        }
                    } else {
                        $qb->where("t_uriage.".$value, $request->operator[$key], $request->value[$key]);
                    }
                }
            }
        }
        $qb->where('t_uriage.jyutyu_kbn', 1);
        $qb->whereNotNull('t_uriage.kaisyu_dt');
        $qb->groupBy(
        	't_uriage.ninusi_cd',
        	'ninusi1_nm',
            'haitatu_dt'
        );
        $cloneQb = clone $qb;
        return [
            'rows' => $qb,
            'total' => $cloneQb->get()->count(),
            
        ];

    }

    private function keyWhere() {
        
    }
}