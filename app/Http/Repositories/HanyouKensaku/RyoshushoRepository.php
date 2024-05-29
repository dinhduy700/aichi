<?php


namespace App\Http\Repositories\HanyouKensaku;


use App\Models\MNinusi;
use App\Models\TUriage;
use Illuminate\Support\Facades\DB;

class RyoshushoRepository
{
    public function getListWithTotalCount($request)
    {
        $qb = TUriage::query()->select('t_uriage.*')
            ->where('genkin_cd', 1)
            ->whereNotNull('syaban');

        $qb->joinMJyomuin()->addSelect('m_jyomuin.jyomuin_nm');
        $qb->leftJoinSub(
            MNinusi::query()->select([
                'ninusi_cd', 'ninusi1_nm',
                DB::raw("COALESCE(seikyu_cd, ninusi_cd) AS seikyu_cd")
            ]), 'ninusi_seikyu',
            't_uriage.ninusi_cd', '=', 'ninusi_seikyu.ninusi_cd'
        )->addSelect('ninusi_seikyu.ninusi1_nm', 'ninusi_seikyu.seikyu_cd');
        $qb->leftJoin('m_ninusi AS m_seikyu', 'ninusi_seikyu.seikyu_cd', '=', 'm_seikyu.ninusi_cd')
            ->addSelect('m_seikyu.zei_keisan_kbn');

        $qb->joinHatuti()->addSelect([
            'm_hatuti.hachaku_nm AS hatuti_nm',//GRID.発地
            'm_hatuti.jyusyo1_nm AS hatuti_jyusyo1_nm',//GRID.発地住所１
            'm_hatuti.jyusyo2_nm AS hatuti_jyusyo2_nm',//GRID.発地住所２
        ]);
        $qb->joinMHachaku()->addSelect([
            'm_hachaku.hachaku_nm',//GRID.着地
            'm_hachaku.jyusyo1_nm AS hachaku_jyusyo1_nm',//GRID.着地住所１
            'm_hachaku.jyusyo2_nm AS hachaku_jyusyo2_nm',//GRID.着地住所２
        ]);
        $qb->joinMHinmei('left', 'm_hinmei', true)->addSelect(['m_hinmei.hinmei_nm', 'm_hinmoku.hinmoku_nm']);//品名, メーカー

        $qb->joinMMeisyoSyubetu()->addSelect('m_meisyo_syubetu.meisyo_nm AS syubetu_nm');

        //金額
        //unchin_kin + tyukei_kin + tukoryo_kin + nieki_kin + syuka_kin + tesuryo_kin
        $kingaku = "("
            . "COALESCE(unchin_kin, 0)"
            . " + COALESCE(tyukei_kin, 0)"
            . " + COALESCE(tukoryo_kin, 0)"
            . " + COALESCE(nieki_kin, 0)"
            . " + COALESCE(syuka_kin, 0)"
            . " + COALESCE(tesuryo_kin, 0)"
            . ")";
        $qb->selectRaw("{$kingaku} AS kingaku");

        $qb->orderBy('t_uriage.haitatu_dt');
        $qb->orderBy('t_uriage.hachaku_cd');
        $qb->orderBy('ninusi_seikyu.seikyu_cd');
        $qb->orderBy('uriage_den_no');

        $labels = configParam("HANYOU_KENSAKU.kinou.ryoshusho_{$request->mode}.labels");
        $qb->selectRaw("? AS kaisya_nm", [data_get($labels, 'kaisya_mei', '')]);//GRID.会社名
        $qb->selectRaw("? AS kaisya_jyusyo", [data_get($labels, 'jyusyo', '')]);//GRID.住所
        $qb->selectRaw("? AS kaisya_tel", [data_get($labels, 'tel', '')]);//GRID.電話番号

        $qb->selectRaw((config("params.TAX_RATE") * 100) . " AS tax_rate");

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
