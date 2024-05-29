<?php


namespace App\Http\Repositories\HanyouKensaku;


use App\Models\TNyukin;

class MihikiateNyukinDenpyoRepository
{
    public function getListWithTotalCount($request)
    {
        $nyukinGaku = "("
            . " + COALESCE(genkin_kin, 0)"
            . " + COALESCE(furikomi_kin, 0)"
            . " + COALESCE(furikomi_tesuryo_kin, 0)"
            . " + COALESCE(tegata_kin, 0)"
            . " + COALESCE(sousai_kin, 0)"
            . " + COALESCE(nebiki_kin, 0)"
            . " + COALESCE(sonota_nyu_kin, 0)"
            . ")";

        $qb = TNyukin::query()->select('t_nyukin.*');
        $qb->selectRaw("{$nyukinGaku} AS nyukin_gaku");

        $qb->leftJoin("t_seikyu_nyukin", "t_nyukin.nyukin_no", '=', "t_seikyu_nyukin.nyukin_no")
            ->addSelect(["t_seikyu_nyukin.seikyu_no"]);

        $qb->where(function($q) use ($nyukinGaku) {
            $q->whereNull("t_seikyu_nyukin.seikyu_no")
                //->whereRaw("{$nyukinGaku} = 0", [], 'OR')
            ;
        });

        $qb->joinMNinusi()->addSelect(['ninusi1_nm']);
        $qb->orderBy("t_nyukin.ninusi_cd");
        $qb->orderBy("t_nyukin.nyukin_no");

        $total = $qb->count();
        return [
            'total' => $total,
            'rows' => $qb,
        ];
    }
}
