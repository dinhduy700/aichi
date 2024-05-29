<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class SeikyuMeisaiRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.haitatu_dt',
            
            'm_hachaku.hachaku_nm',
            'm_hinmoku.hinmoku_nm',
            'm_hinmei.hinmei_nm',
            't_uriage.su',
            DB::raw('m_meisyo_syubetu.meisyo_nm as syubetu_nm'),
            DB::raw('COALESCE(t_uriage.unchin_kin, 0) + COALESCE(t_uriage.tukoryo_kin, 0) + COALESCE(t_uriage.nieki_kin, 0) + COALESCE(t_uriage.syuka_kin, 0) + COALESCE(t_uriage.tesuryo_kin, 0) as field_no7'),

            // 'm_jyomuin.jyomuin_nm',
            't_uriage.tyukei_kin',
            't_uriage.seikyu_kin_tax',
            DB::raw('COALESCE(t_uriage.unchin_kin, 0) + COALESCE(t_uriage.tukoryo_kin, 0) + COALESCE(t_uriage.nieki_kin, 0) + COALESCE(t_uriage.syuka_kin, 0) + COALESCE(t_uriage.tesuryo_kin, 0) + COALESCE(t_uriage.tyukei_kin, 0) + COALESCE(t_uriage.seikyu_kin_tax, 0)  as field_no10'),
            'm_hachaku.hachaku_cd'
        );
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
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
        $qb->orderBy('t_uriage.haitatu_dt', 'desc');
        return [
            'rows' => $qb,
            'total' => $qb->count(),
        ];

    }
}