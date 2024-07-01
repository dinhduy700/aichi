<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class GenkinKbnChecklistRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.syaban',
            't_uriage.jyomuin_cd',
            'm_jyomuin.jyomuin_nm',
            't_uriage.haitatu_dt',
            't_uriage.jikoku',
            
            'm_ninusi.ninusi_ryaku_nm',
            // 'm_hachaku.hachaku_nm',
            't_uriage.hachaku_nm',
            'm_hinmoku.hinmoku_nm',
            DB::raw('m_meisyo_syubetu.meisyo_nm as syubetu_nm'),
            't_uriage.su',
            // 'm_hinmei.hinmei_nm',
            't_uriage.hinmei_nm',
            't_uriage.syuka_dt',
            't_uriage.sitadori',
            't_uriage.jyotai',
            DB::raw('m_meisyo_gyosya.meisyo_nm as gyosya_nm'),
            't_uriage.unchin_kin',
            't_uriage.tyukei_kin',
            't_uriage.tukoryo_kin',
            't_uriage.syuka_kin',
            't_uriage.tesuryo_kin',
            't_uriage.unten_kin',
            't_uriage.biko',
            't_uriage.uriage_den_no'
           
        );

        $qb->leftJoin('m_meisyo as m_meisyo_gyosya', function($query) {
            $query->on('m_meisyo_gyosya.meisyo_cd', '=', 't_uriage.gyosya_cd');
            $query->where('m_meisyo_gyosya.meisyo_kbn', '=', configParam('MEISYO_KBN_GYOSYA'));
        });
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
        $qb->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd');
        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd');
        $qb->leftJoin('m_hinmei', 'm_hinmei.hinmei_cd', 't_uriage.hinmei_cd');
        $qb->leftJoin('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd');
        $qb->leftJoin('m_meisyo as m_meisyo_syubetu', function($query) {
            $query->on('m_meisyo_syubetu.meisyo_cd', '=', 't_uriage.syubetu_cd');
            $query->where('m_meisyo_syubetu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYUBETU'));
        });
        

        if (!empty($request->field)) {
            $qb->where(function ($query) use ($request) {
                foreach ($request->field as $key => $value) {
                    if ($value) {
                        if (!empty($request->logical_operator[$key - 1])) {
                            $logicalOperator = strtolower($request->logical_operator[$key - 1]);
                            if ($logicalOperator == 'and') {
                                $query->where("t_uriage." . $value, $request->operator[$key], $request->value[$key]);
                            } else {
                                $query->orWhere("t_uriage." . $value, $request->operator[$key], $request->value[$key]);
                            }
                        } else {
                            $query->where("t_uriage." . $value, $request->operator[$key], $request->value[$key]);
                        }
                    }
                }
            });
        }
        $qb->where('t_uriage.genkin_cd', 1);
        return [
            'rows' => $qb,
            'total' => $qb->count(),
        ];

    }
}