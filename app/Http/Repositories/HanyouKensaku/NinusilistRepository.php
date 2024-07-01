<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class NinusilistRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.unso_dt',
            't_uriage.syaban',
            'm_jyomuin.jyomuin_nm',
            'm_ninusi.ninusi_ryaku_nm',
            DB::raw('m_meisyo_syubetu.meisyo_nm as syubetu_nm'),
            DB::raw('m_meisyo_gyosya.meisyo_nm as gyosya_nm'),
            // 'm_hachaku.hachaku_nm',
            't_uriage.hachaku_nm',
            // DB::raw('m_hatuti.hachaku_nm as hatuti_nm'),
            DB::raw('t_uriage.hatuti_hachaku_nm as hatuti_nm'),
            'm_hinmoku.hinmoku_nm',
            // 'm_hinmei.hinmei_nm',
            't_uriage.hinmei_nm',
            't_uriage.su',
            DB::raw('m_meisyo_tani.meisyo_nm as tani_nm'),
            't_uriage.unchin_kin',
            't_uriage.tyukei_kin',
            't_uriage.tukoryo_kin',
            't_uriage.syuka_kin',
            't_uriage.tesuryo_kin',
            't_uriage.unten_kin',

            't_uriage.kaisyu_kin',
            'm_yousya.yousya_ryaku_nm',
            't_uriage.yosya_tyukei_kin',
            't_uriage.yosya_tukoryo_kin',
            DB::raw('COALESCE(t_uriage.yosya_tyukei_kin, 0) + COALESCE(t_uriage.yosya_tukoryo_kin, 0) + COALESCE(t_uriage.yosya_kin_tax, 0) as total_yosya')
           
        );

        $qb->leftJoin('m_meisyo as m_meisyo_gyosya', function($query) {
            $query->on('m_meisyo_gyosya.meisyo_cd', '=', 't_uriage.gyosya_cd');
            $query->where('m_meisyo_gyosya.meisyo_kbn', '=', configParam('MEISYO_KBN_GYOSYA'));
        });
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
        $qb->leftJoin('m_hachaku as m_hatuti', 'm_hatuti.hachaku_cd', 't_uriage.hatuti_cd');
        $qb->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd');
        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd');
        $qb->leftJoin('m_hinmei', 'm_hinmei.hinmei_cd', 't_uriage.hinmei_cd');
        $qb->leftJoin('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd');
        $qb->leftJoin('m_meisyo as m_meisyo_syubetu', function($query) {
            $query->on('m_meisyo_syubetu.meisyo_cd', '=', 't_uriage.syubetu_cd');
            $query->where('m_meisyo_syubetu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYUBETU'));
        });

        $qb->leftJoin('m_meisyo as m_meisyo_tani', function($query) {
            $query->on('m_meisyo_tani.meisyo_cd', '=', 't_uriage.tani_cd');
            $query->where('m_meisyo_tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        });
        $qb->leftJoin('m_yousya', 'm_yousya.yousya_cd', 't_uriage.yousya_cd');

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
        return [
            'rows' => $qb,
            'total' => $qb->count(),
        ];

    }
}