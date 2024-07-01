<?php 
namespace App\Http\Repositories\HanyouKensaku;

use Illuminate\Http\Response;
use App\Models\TUriage;
use DB;

class IdoharigamiRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::select(
            't_uriage.haitatu_dt',
            'm_hinmoku.hinmoku_nm',
            't_uriage.su',
            // 'm_hinmei.hinmei_nm',
            't_uriage.hinmei_nm',
            't_uriage.syuka_dt',
            't_uriage.uriage_den_no',
            // 'm_hachaku.hachaku_nm',
            't_uriage.hachaku_nm',
            DB::raw('m_meisyo_tani.meisyo_nm as tani_nm'),
            't_uriage.biko',
           'm_jyomuin.jyomuin_nm'
        );
        $qb->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd');
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_uriage.hachaku_cd');
        $qb->leftJoin('m_hinmei', 'm_hinmei.hinmei_cd', 't_uriage.hinmei_cd');
        $qb->leftJoin('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd');
        $qb->leftJoin('m_meisyo as m_meisyo_tani', function($query) {
            $query->on('m_meisyo_tani.meisyo_cd', '=', 't_uriage.tani_cd');
            $query->where('m_meisyo_tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        });
        

        if (!empty($request->field)) {
            $qb->where(function ($query) use ($request) {
                foreach ($request->field as $key => $value) {
                    if ($value) {
                        if($value == 'hinmoku_cd') {
                            $table = 'm_hinmoku';
                        } else {
                            $table = 't_uriage';
                        }
                        if (!empty($request->logical_operator[$key - 1])) {
                            $logicalOperator = strtolower($request->logical_operator[$key - 1]);
                            if ($logicalOperator == 'and') {
                                $query->where("{$table}." . $value, $request->operator[$key], $request->value[$key]);
                            } else {
                                $query->orWhere("{$table}." . $value, $request->operator[$key], $request->value[$key]);
                            }
                        } else {
                            $query->where("{$table}." . $value, $request->operator[$key], $request->value[$key]);
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