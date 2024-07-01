<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class YosyaRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.*',
            'm_yousya.yousya_ryaku_nm',
            'm_ninusi.ninusi_ryaku_nm',
            DB::raw('meisyo_syubetu.meisyo_nm as syubetu_nm'),
            DB::raw('meisyo_gyosya.meisyo_nm as gyosya_nm'),
            // DB::raw('m_hatuti.hachaku_nm as hatuti_nm'),
            DB::raw('t_uriage.hatuti_hachaku_nm as hatuti_nm'),
            // 'm_hachaku.hachaku_nm',
            't_uriage.hachaku_nm',
            // 'm_hinmei.hinmei_nm',
            't_uriage.hinmei_nm',
            'm_hinmoku.hinmoku_nm',
            DB::raw('meisyo_tani.meisyo_nm as tani_nm'),
            DB::raw('COALESCE(t_uriage.yosya_tyukei_kin, 0) + COALESCE(t_uriage.yosya_tukoryo_kin, 0) as total')
        );
        $qb->leftJoin('m_yousya', 'm_yousya.yousya_cd', 't_uriage.yousya_cd');
        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd');
        $qb->leftJoin('m_meisyo as meisyo_syubetu', function($query) {
            $query->on('meisyo_syubetu.meisyo_cd', '=', 't_uriage.syubetu_cd');
            $query->where('meisyo_syubetu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYUBETU'));
        });
        $qb->leftJoin('m_meisyo as meisyo_gyosya', function($query) {
            $query->on('meisyo_gyosya.meisyo_cd', '=', 't_uriage.gyosya_cd');
            $query->where('meisyo_gyosya.meisyo_kbn', '=', configParam('MEISYO_KBN_GYOSYA'));
        });
        $qb->leftJoin('m_hachaku as m_hatuti', 'm_hatuti.hachaku_cd', 't_uriage.hatuti_cd');
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
        $qb->leftJoin('m_hinmei', 'm_hinmei.hinmei_cd', 't_uriage.hinmei_cd');
        $qb->leftJoin('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd');
        $qb->leftJoin('m_meisyo as meisyo_tani', function($query) {
            $query->on('meisyo_tani.meisyo_cd', '=', 't_uriage.tani_cd');
            $query->where('meisyo_tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        });

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
        $total = $qb->count();
        return [
            'total' => $total,
            'rows' => $qb,
        ];
    }
}