<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class GenkinKaishuChecklistRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            DB::raw('null as field_no1'),
            DB::raw('\'売上\' as field_no2'),
            DB::raw('m_meisyo_gyosya.meisyo_nm as gyosya_nm'),
            // 'm_hachaku.hachaku_nm',
            't_uriage.hachaku_nm',
            DB::raw('COALESCE(t_uriage.unchin_kin, 0) + COALESCE(t_uriage.tyukei_kin, 0) + COALESCE(t_uriage.tukoryo_kin, 0) + COALESCE(t_uriage.nieki_kin, 0) + COALESCE(t_uriage.syuka_kin, 0) + COALESCE(t_uriage.tesuryo_kin, 0) as field_no6'),
            'm_jyomuin.jyomuin_nm',
            't_uriage.haitatu_dt'
        );

        $qb->leftJoin('m_meisyo as m_meisyo_gyosya', function($query) {
            $query->on('m_meisyo_gyosya.meisyo_cd', '=', 't_uriage.gyosya_cd');
            $query->where('m_meisyo_gyosya.meisyo_kbn', '=', configParam('MEISYO_KBN_GYOSYA'));
        });
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
        $qb->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd');
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
        $qb->whereNotNull('t_uriage.kaisyu_dt');
        return [
            'rows' => $qb,
            'total' => $qb->count(),
        ];

    }
}