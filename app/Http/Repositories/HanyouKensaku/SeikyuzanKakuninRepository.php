<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TSeikyu;
use DB;

class SeikyuzanKakuninRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TSeikyu::select(
            't_seikyu.ninusi_cd',
            'm_ninusi.ninusi1_nm', 
            't_seikyu.seikyu_sime_dt',
            't_seikyu.konkai_torihiki_kin',
            DB::raw('COALESCE(t_seikyu.genkin_kin, 0) + COALESCE(t_seikyu.furikomi_kin, 0) + COALESCE(t_seikyu.furikomi_tesuryo_kin, 0) + COALESCE(t_seikyu.tegata_kin, 0) + COALESCE(t_seikyu.sousai_kin, 0) + COALESCE(t_seikyu.nebiki_kin, 0) + COALESCE(t_seikyu.sonota_nyu_kin, 0) as total_no5'),
            DB::raw('COALESCE(t_seikyu.konkai_torihiki_kin, 0) - (COALESCE(t_seikyu.genkin_kin, 0) + COALESCE(t_seikyu.furikomi_kin, 0) + COALESCE(t_seikyu.furikomi_tesuryo_kin, 0) + COALESCE(t_seikyu.tegata_kin, 0) + COALESCE(t_seikyu.sousai_kin, 0) + COALESCE(t_seikyu.nebiki_kin, 0) + COALESCE(t_seikyu.sonota_nyu_kin, 0)) as total_no6')
        );
        $qb->joinMNinusi('left');
        if(!empty($request->field)) {
            foreach($request->field as $key => $value) {
                if($value) {
                    if($value == 'konkai_torihiki_kin_flg') {
                        if($request->value[$key] == 1 && $request->operator[$key] == '=') {
                            $qb->where('t_seikyu.konkai_torihiki_kin', '>',  DB::raw('COALESCE(t_seikyu.genkin_kin, 0) + COALESCE(t_seikyu.furikomi_kin, 0) + COALESCE(t_seikyu.furikomi_tesuryo_kin, 0) + COALESCE(t_seikyu.tegata_kin, 0) + COALESCE(t_seikyu.sousai_kin, 0) + COALESCE(t_seikyu.nebiki_kin, 0) + COALESCE(t_seikyu.sonota_nyu_kin, 0)'));
                        }
                        continue;
                    }
                    if(!empty($request->logical_operator[$key -1])) {
                        if(strtolower($request->logical_operator[$key -1]) == 'and') {
                            $qb->where("t_seikyu.".$value, $request->operator[$key], $request->value[$key]);
                        } else {
                            $qb->orWhere("t_seikyu.".$value, $request->operator[$key], $request->value[$key]);
                        }
                    } else {
                        $qb->where("t_seikyu.".$value, $request->operator[$key], $request->value[$key]);
                    }
                }
            }
        }
        return [
            'rows' => $qb,
            'total' => $qb->count(),
        ];

    }
}