<?php

namespace App\Http\Repositories\Nyusyuko;

use App\Models\TNyusyukoHead;
use App\Models\TZaiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZaikoListRepository
{
    const EXP_PRINT_CSV_HEADER = '1';
    const EXP_PRINT_BY_LOT = '2';
    const EXP_PRINT_WITH_AMOUNT = '3';

    public function getOptionOpts()
    {
        return [
            $this::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力'],
            $this::EXP_PRINT_WITH_AMOUNT => ['text' => '金額印字あり'],
            $this::EXP_PRINT_BY_LOT => ['text' => 'ロット別に出力'],
        ];
    }

    public function getOptionHinmei()
    {
        return [
            'start' => [
                'text' => 'で始まる',
                'where' => function(&$q, $value) {
                    $q->where('m_soko_hinmei.hinmei_nm', 'ILIKE', makeEscapeStr($value) .'%');
                }
            ],
            'contain' => [
                'text' => 'を含む',
                'where' => function(&$q, $value) {
                    $q->where('m_soko_hinmei.hinmei_nm', 'ILIKE', '%' . makeEscapeStr($value) .'%');
                }
            ]
        ];
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);

        foreach ($cloneReq->all() as $key => $value) {
            $fromTo = [
                'bumon_cd', 'ninusi_cd', 'hinmei_cd', 'soko_cd',
            ];
            foreach ($fromTo as $field) {
                if (is_null($value)) continue;
                if ($key == "{$field}_from") $qb->where("t_zaiko.{$field}", '>=', $value);
                if ($key == "{$field}_to") $qb->where("t_zaiko.{$field}", '<=', $value);
            }
        }

        if (!empty( $cloneReq->get('hinmei_nm'))) {
            $fnc = data_get($this->getOptionHinmei(), $cloneReq->get('hinmei_like', 'start') . ".where");
            $fnc($qb, $cloneReq->get('hinmei_nm'));
        }

        return $qb;
    }

    public function qbExport($request, $routeNm = null)
    {
        $kijyunDt = $request->kijyun_dt;
        if (empty($kijyunDt)) return null;

        $includeLot = true;
        if (!in_array(self::EXP_PRINT_BY_LOT, data_get($request, 'option', []))) {
            $includeLot = false;
        }

        $groups = ['t_zaiko.bumon_cd', 't_zaiko.ninusi_cd', 't_zaiko.soko_cd', 't_zaiko.hinmei_cd'];
        if ($includeLot) $groups = array_merge($groups, ['t_zaiko.lot1', 't_zaiko.lot2', 't_zaiko.lot3']);
        $tZaiko = TZaiko::select($groups)->selectRaw("SUM(t_zaiko.su) AS su")->groupBy($groups);

        $qb = TZaiko::query()->fromSub($tZaiko, 't_zaiko');
        $qb->select('t_zaiko.*');
        $qb->joinMBumon()->addSelect(['m_bumon.bumon_nm']);
        $qb->joinMSoko()->addSelect(['m_soko.soko_nm']);
        $qb->joinMNinusi()->addSelect(['m_ninusi.ninusi_ryaku_nm']);
        $qb->joinMSokoHinmei()->addSelect([
            'm_soko_hinmei.hinmei_nm',
            'm_soko_hinmei.kikaku',
            'm_soko_hinmei.irisu',
            'm_soko_hinmei.case_cd',
            'm_soko_hinmei.bara_tani',
            'm_soko_hinmei.uke_tanka',
        ]);

        $qb->leftJoin("m_meisyo AS m_meisyo_case", function ($j){
            $j->on('m_soko_hinmei.case_cd', '=', 'm_meisyo_case.meisyo_cd');
            $j->where('m_meisyo_case.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        })->addSelect('m_meisyo_case.meisyo_nm AS case_nm');
        $qb->leftJoin("m_meisyo AS m_meisyo_bara_tani", function ($j){
            $j->on('m_soko_hinmei.bara_tani', '=', 'm_meisyo_bara_tani.meisyo_cd');
            $j->where('m_meisyo_bara_tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        })->addSelect('m_meisyo_bara_tani.meisyo_nm AS bara_tani_nm');

        //
        $groupBy = array_merge(
            [
                't_nyusyuko_head.bumon_cd',
                't_nyusyuko_head.ninusi_cd',
                't_nyusyuko_meisai.hinmei_cd',
                't_nyusyuko_meisai.soko_cd',
            ],
            ($includeLot ? ['t_nyusyuko_meisai.lot1', 't_nyusyuko_meisai.lot2', 't_nyusyuko_meisai.lot3'] : [])
        );

        $nyusyuko = TNyusyukoHead::query()->joinMeisai()
            ->select(array_merge($groupBy, [
                // 1:入庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN t_nyusyuko_meisai.su ELSE 0 END) AS in_su"),
                // 2:出庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN t_nyusyuko_meisai.su ELSE 0 END) AS out_su"),

                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '4' THEN t_nyusyuko_meisai.su ELSE 0 END) AS kbn4_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '5' THEN t_nyusyuko_meisai.su ELSE 0 END) AS kbn5_su"),
            ]))
            ->where('t_nyusyuko_head.kisan_dt', '>', $kijyunDt)
            ->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(config('params.NYUSYUKO_KBN_SUPPORT')))
            ->groupBy($groupBy);

        $qb->leftJoinSub($nyusyuko, 'nyusyuko', function($j) use ($includeLot) {
            $j->on('nyusyuko.bumon_cd', '=', 't_zaiko.bumon_cd');
            $j->on('nyusyuko.ninusi_cd', '=', 't_zaiko.ninusi_cd');
            $j->on('nyusyuko.soko_cd', '=', 't_zaiko.soko_cd');
            $j->on('nyusyuko.hinmei_cd', '=', 't_zaiko.hinmei_cd');
            if ($includeLot) {
                $j->on('nyusyuko.lot1', '=', 't_zaiko.lot1');
                $j->on('nyusyuko.lot2', '=', 't_zaiko.lot2');
                $j->on('nyusyuko.lot3', '=', 't_zaiko.lot3');
            }
        });


        $kijyunSu = "(COALESCE(t_zaiko.su,0) - COALESCE(in_su,0) + COALESCE(out_su,0) - COALESCE(kbn4_su,0) - COALESCE(kbn5_su,0))";

        $qb->selectRaw("{$kijyunSu} AS sousu");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$kijyunSu}, m_soko_hinmei.irisu)"
            . " ELSE {$kijyunSu}"
            . " END AS case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$kijyunSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS hasu");
        $qb->addSelect([
            DB::raw("({$kijyunSu} * COALESCE(m_soko_hinmei.bara_tani_juryo, 0)) AS jyuryo"),
            DB::raw("({$kijyunSu} * COALESCE(m_soko_hinmei.uke_tanka, 0)) AS kingaku"),
        ]);

        $qb->distinct();

        return $qb;
    }
}
