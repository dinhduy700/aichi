<?php


namespace App\Http\Repositories\HanyouKensaku;


use App\Models\MNinusi;
use App\Models\TUriage;
use Illuminate\Support\Facades\DB;

class NohinMeisaiRepository
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::query()->select('t_uriage.*');
        $qb->joinMHachaku()->addSelect('m_hachaku.hachaku_nm');
        $qb->joinMHinmei('left', 'm_hinmei', true)->addSelect(['hinmoku_nm', 'hinmei_nm']);
        $qb->joinMMeisyoSyubetu()->addSelect('m_meisyo_syubetu.meisyo_nm AS syubetu_nm');
        $qb->leftJoinSub(
            MNinusi::query()->select(['ninusi_cd', DB::raw("COALESCE(seikyu_cd, ninusi_cd) AS seikyu_cd")])->distinct(),
            'ninusi_seikyu', 't_uriage.ninusi_cd', '=', 'ninusi_seikyu.ninusi_cd'
        );
        $qb->leftJoin('m_ninusi AS m_seikyu', 'ninusi_seikyu.seikyu_cd', '=', 'm_seikyu.ninusi_cd')
            ->addSelect('m_seikyu.zei_keisan_kbn');


        //基本運賃=基本運賃＋通行料等＋荷役料＋集荷料＋手数料
        //kihon_unchin=unchin_kin+tukoryo_kin+nieki_kin+syuka_kin+tesuryo_kin
        $kihonUnchin = "("
            . "COALESCE(unchin_kin, 0)"
            . " + COALESCE(tukoryo_kin, 0)"
            . " + COALESCE(nieki_kin, 0)"
            . " + COALESCE(syuka_kin, 0)"
            . " + COALESCE(tesuryo_kin, 0)"
            . ")";
        $qb->selectRaw("{$kihonUnchin} AS kihon_unchin");

        //税込み金額 zeikomi_kingaku
        $qb->selectRaw("({$kihonUnchin} + COALESCE(tyukei_kin, 0) + COALESCE(seikyu_kin_tax, 0)) AS zeikomi_kingaku");

        $qb->orderBy('t_uriage.haitatu_dt');
        $qb->orderBy('t_uriage.hachaku_cd');
        $qb->orderBy('ninusi_seikyu.seikyu_cd');
        $qb->orderBy('m_hinmei.hinmoku_cd');
        $qb->orderBy('t_uriage.hinmei_cd');
        $qb->orderBy('uriage_den_no');

        if(!empty($request->field)) {
            $qb->where(function ($q) use ($request) {
                foreach($request->field as $key => $value) {
                    if($value && in_array($value, ['haitatu_dt', 'hachaku_cd'])) {
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
