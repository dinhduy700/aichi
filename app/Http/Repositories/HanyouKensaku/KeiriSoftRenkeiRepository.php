<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class KeiriSoftRenkeiRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.unso_dt',
            DB::raw('\'売掛\' as field_no2'),
            't_uriage.bumon_cd',
            'm_bumon.bumon_nm',
            't_uriage.ninusi_cd',
            'm_ninusi.ninusi_ryaku_nm',
            DB::raw('null as field_no7'),
            'm_hachaku.atena',
            DB::raw('COALESCE(t_uriage.unchin_kin, 0) + COALESCE(t_uriage.tyukei_kin, 0) + COALESCE(t_uriage.nieki_kin, 0) + COALESCE(t_uriage.syuka_kin, 0) + COALESCE(t_uriage.tesuryo_kin, 0) as field_no9'),
            DB::raw('COALESCE(t_uriage.unchin_kin, 0) + COALESCE(t_uriage.tyukei_kin, 0) + COALESCE(t_uriage.nieki_kin, 0) + COALESCE(t_uriage.syuka_kin, 0) + COALESCE(t_uriage.tesuryo_kin, 0) + COALESCE(t_uriage.seikyu_kin_tax, 0) as field_no10'),
            't_uriage.syaban',
            't_uriage.jyomuin_cd',
            'm_jyomuin.jyomuin_nm',
            't_uriage.tukoryo_kin'
        );
        $qb->leftJoin('m_bumon', 'm_bumon.bumon_cd', 't_uriage.bumon_cd');
        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd');
        $qb->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd');
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
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

        return [
            'rows' => $qb,
            'total' => $qb->count(),
        ];

    }
}