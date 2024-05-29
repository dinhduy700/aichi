<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class SuitochoNyuryokuListRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.kaisyu_dt',
            't_uriage.syaban',
            't_uriage.bumon_cd',
            't_uriage.ninusi_cd',
            't_uriage.jyomuin_cd',
            't_uriage.gyosya_cd',
            'm_ninusi.ninusi1_nm',
            'm_bumon.bumon_nm',
            'm_jyomuin.jyomuin_nm',
            DB::raw('\'売上\' as field_no4'),
            DB::raw('meisyo_gyosya.meisyo_nm as gyosya_nm'),
            DB::raw('COALESCE(SUM(t_uriage.unchin_kin), 0) + COALESCE(SUM(t_uriage.tyukei_kin), 0) + COALESCE(SUM(t_uriage.tukoryo_kin), 0) + COALESCE(SUM(t_uriage.nieki_kin), 0) + COALESCE(SUM(t_uriage.syuka_kin), 0) + COALESCE(SUM(t_uriage.tesuryo_kin), 0) + COALESCE(SUM(t_uriage.seikyu_kin_tax), 0) as total_1'),
            DB::raw('COALESCE(SUM(t_uriage.unchin_kin), 0) + COALESCE(SUM(t_uriage.tyukei_kin), 0) + COALESCE(SUM(t_uriage.tukoryo_kin), 0) + COALESCE(SUM(t_uriage.nieki_kin), 0) + COALESCE(SUM(t_uriage.syuka_kin), 0) + COALESCE(SUM(t_uriage.tesuryo_kin), 0) as total_2'),
            't_uriage.haitatu_dt'
        );

        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd')
        ;
        $qb->leftJoin('m_meisyo as meisyo_gyosya', function($query) {
            $query->on('meisyo_gyosya.meisyo_cd', '=', 't_uriage.gyosya_cd');
            $query->where('meisyo_gyosya.meisyo_kbn', '=', configParam('MEISYO_KBN_GYOSYA'));
        });

        $qb->leftJoin('m_bumon', 'm_bumon.bumon_cd', 't_uriage.bumon_cd');

        $qb->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd');

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
            'kaisyu_dt',
            'syaban',
            't_uriage.bumon_cd',
            'bumon_nm',
            't_uriage.ninusi_cd',
            'ninusi1_nm',
            't_uriage.jyomuin_cd',
            'jyomuin_nm',
            't_uriage.gyosya_cd',
            'meisyo_gyosya.meisyo_nm',
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