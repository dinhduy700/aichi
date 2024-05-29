<?php


namespace App\Http\Repositories\HanyouKensaku;


use App\Models\TUriage;

class MikakuteiUnchinListRepository
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::query()->select('t_uriage.*');
        $qb->where('t_uriage.unchin_mikakutei_kbn', '1');

        $qb->joinMNinusi()->addSelect('m_ninusi.ninusi1_nm');
        $qb->joinMJyomuin()->addSelect('m_jyomuin.jyomuin_nm');
        $qb->joinHatuti()->addSelect('m_hatuti.hachaku_nm AS hatuti_nm');
        $qb->joinMHachaku()->addSelect('m_hachaku.hachaku_nm');
        $qb->joinMHinmei()->addSelect('m_hinmei.hinmei_nm');
        $qb->joinMMeisyoTani()->addSelect('m_meisyo_tani.meisyo_nm AS tani_nm');

        $qb->orderBy('t_uriage.seikyu_sime_dt');
        $qb->orderBy('t_uriage.ninusi_cd');
        $qb->orderBy('t_uriage.uriage_den_no');

        if(!empty($request->field)) {
            $qb->where(function ($q) use ($request) {
                foreach($request->field as $key => $value) {
                    if($value) {
                        if(!empty($request->logical_operator[$key -1])) {
                            if(strtolower($request->logical_operator[$key -1]) == 'and') {
                                $q->where("t_uriage.".$value, $request->operator[$key], $request->value[$key]);
                            } else {
                                $q->orWhere("t_uriage.".$value, $request->operator[$key], $request->value[$key]);
                            }
                        } else {
                            $q->where("t_uriage.".$value, $request->operator[$key], $request->value[$key]);
                        }
                    }
                }
            });
        }

        $total = $qb->count();
        return [
            'total' => $total,
            'rows' => $qb,
        ];
    }
}
