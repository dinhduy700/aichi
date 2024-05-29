<?php


namespace App\Http\Repositories\Seikyu;


use App\Models\TNyukin;
use App\Models\TSeikyu;
use App\Models\TUriage;
use Illuminate\Support\Facades\Auth;

class SeikyuKakuteiRepository
{
    // 12.請求確定処理
    public function getList($request)
    {
        $qb = (new SeikyuListRepository())->getListBuilder($request);
        return ['total' => $qb->count(), 'rows' => $qb];
    }

    public function setKakutei($request)
    {
        $updUserCd = Auth::id();

        // set where from selected checkbox
        $qb = TSeikyu::query();
        (new SeikyuListRepository())->applySelectedItemsToBuilder($request, $qb, 'selected', false);

        // update t_seikyu
        (clone $qb)->update([
            'seikyu_kakutei_flg' => 1,
            'upd_user_cd' => $updUserCd,
        ]);

        // update t_uriage
        TUriage::query()->joinSub(clone $qb, 't_seikyu', function ($j) {
            $j->on("t_uriage.seikyu_no", "=", "t_seikyu.seikyu_no");
            $j->on("t_uriage.seikyu_sime_dt", "=", "t_seikyu.seikyu_sime_dt");
        })->update([
            't_uriage.sime_kakutei_kbn' => 1,
            't_uriage.upd_user_cd' => $updUserCd,
        ]);

        // update t_nyukin
        TNyukin::query()->join('t_seikyu_nyukin', 't_seikyu_nyukin.nyukin_no', '=', 't_nyukin.nyukin_no')
            ->joinSub(clone $qb, 't_seikyu', "t_seikyu_nyukin.seikyu_no", "=", "t_seikyu.seikyu_no")
            ->update([
                't_nyukin.sime_kakutei_kbn' => 1,
                't_nyukin.upd_user_cd' => $updUserCd,
            ]);
    }
}
