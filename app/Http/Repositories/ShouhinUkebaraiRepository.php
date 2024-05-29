<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShouhinUkebaraiRepository
{
    const EXP_PRINT_CSV_HEADER = '1';

    const EXP_PRINT_GROUP = '2';

    public function getOptionOpts()
    {
        return [
            $this::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力（M）'],
            $this::EXP_PRINT_GROUP => ['text' => 'ロット別に出力']
        ];
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);
        return $qb;
    }

    private function qbBase($request, $routeNm, $isGroupLot)
    {
        $groups = ['t_zaiko.bumon_cd', 't_zaiko.ninusi_cd', 't_zaiko.soko_cd', 't_zaiko.hinmei_cd'];

        if ($isGroupLot) {
            $groups = array_merge($groups, [
                't_zaiko.lot1',
                't_zaiko.lot2',
                't_zaiko.lot3'
            ]);
        }

        $tZaiko = DB::query()
            ->select($groups)
            ->selectRaw("SUM(t_zaiko.su) AS su")
            ->from('t_zaiko')
            ->groupBy($groups);

        $qb = DB::query()
            ->fromSub($tZaiko, 't_zaiko')
            ->select('t_zaiko.*');
        $subQueryZaikoSu = $this->subQuerySum($request, $routeNm, $isGroupLot);
        $subQueryTotalSu = $this->subQuerySum($request, $routeNm, $isGroupLot,true);
        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd' => ['t_zaiko.bumon_cd', 't_nyusyuko_head.bumon_cd'],
                'ninusi_cd' => ['t_zaiko.ninusi_cd', 't_nyusyuko_head.ninusi_cd'],
                'hinmei_cd' => ['t_zaiko.hinmei_cd', 't_nyusyuko_meisai.hinmei_cd']
            ];
            foreach ($fromTo as $reqKey => $fields) {
                list($qbField, $subQueryField) = $fields;
                if (is_null($value)) {
                    continue;
                }
                if ($key == "{$reqKey}_from") {
                    $qb->where($qbField, '>=', $value);
                    $subQueryZaikoSu->where($subQueryField, '>=', $value);
                    $subQueryTotalSu->where($subQueryField, '>=', $value);
                }

                if ($key == "{$reqKey}_to") {
                    $qb->where($qbField, '<=', $value);
                    $subQueryZaikoSu->where($subQueryField, '<=', $value);
                    $subQueryTotalSu->where($subQueryField, '<=', $value);
                }
            }
        }
        $qb->leftJoinSub($subQueryZaikoSu, 't1', function ($join) use ($isGroupLot) {
            $join->on('t_zaiko.bumon_cd', 't1.bumon_cd')
                ->on('t_zaiko.ninusi_cd', 't1.ninusi_cd')
                ->on('t_zaiko.hinmei_cd', 't1.hinmei_cd')
                ->on('t_zaiko.soko_cd', 't1.soko_cd');
            if ($isGroupLot) {
                $join->on(function ($query) {
                    $query->on('t_zaiko.lot1', 't1.lot1')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot1')
                                ->whereNull('t1.lot1');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot2', 't1.lot2')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot2')
                                ->whereNull('t1.lot2');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot3', 't1.lot3')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot3')
                                ->whereNull('t1.lot3');
                        });
                });
            }
        });
        $qb->leftJoinSub($subQueryTotalSu, 't2', function ($join) use ($isGroupLot) {
            $join->on('t_zaiko.bumon_cd', 't2.bumon_cd')
                ->on('t_zaiko.ninusi_cd', 't2.ninusi_cd')
                ->on('t_zaiko.hinmei_cd', 't2.hinmei_cd')
                ->on('t_zaiko.soko_cd', 't2.soko_cd');
            if ($isGroupLot) {
                $join->on(function ($query) {
                    $query->on('t_zaiko.lot1', 't2.lot1')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot1')
                                ->whereNull('t2.lot1');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot2', 't2.lot2')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot2')
                                ->whereNull('t2.lot2');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot3', 't2.lot3')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot3')
                                ->whereNull('t2.lot3');
                        });
                });
            }
        });
        return $qb;
    }

    public function qbExport($request, $routeNm = null)
    {
        $isGroupLot = true;
        if (!in_array(self::EXP_PRINT_GROUP, data_get($request, 'option', []))) {
            $isGroupLot = false;
        }
        $qb = $this->qbBase($request, $routeNm, $isGroupLot);

        //繰越数
        $zaikoSu = "(COALESCE(t_zaiko.su,0) - COALESCE(t1.in_su,0) + COALESCE(t1.out_su,0) - COALESCE(t1.exist_su,0) - COALESCE(t1.moving_su,0))";
        $qb->selectRaw("{$zaikoSu} AS zaiko_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$zaikoSu}, m_soko_hinmei.irisu)"
            . " ELSE {$zaikoSu}"
            . " END AS zaiko_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$zaikoSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS zaiko_hasu");
        $qb->selectRaw("({$zaikoSu} * COALESCE(m_soko_hinmei.bara_tani_juryo, 0)) AS zaiko_jyuryo");

        //出庫数
        $inSu = "(COALESCE(t2.in_su,0) + COALESCE(t2.exist_su,0) + (CASE WHEN t2.moving_su > 0 THEN t2.moving_su ELSE 0 END))";
        $qb->selectRaw("{$inSu} as in_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$inSu}, m_soko_hinmei.irisu)"
            . " ELSE {$inSu}"
            . " END AS in_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$inSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS in_hasu");
        $qb->selectRaw("({$inSu} * COALESCE(t2.jyuryo, 0)) AS in_jyuryo");

        //出庫数
        $outSu = "(COALESCE(t2.out_su,0) + (CASE WHEN t2.moving_su < 0 THEN t2.moving_su * (-1) ELSE 0 END))";
        $qb->selectRaw("{$outSu} as out_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$outSu}, m_soko_hinmei.irisu)"
            . " ELSE {$outSu}"
            . " END AS out_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$outSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS out_hasu");
        $qb->selectRaw("({$outSu} * COALESCE(t2.jyuryo, 0)) AS out_jyuryo");

        //残高
        $zankou = "({$zaikoSu} + COALESCE({$inSu}, 0) - COALESCE({$outSu}, 0))";
        $qb->addSelect(DB::raw("{$zankou} AS zankou_su"));
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$zankou}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS zankou_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$zankou}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS zankou_hasu");
        $qb->selectRaw("({$zankou} * COALESCE(m_soko_hinmei.bara_tani_juryo, 0)) AS zankou_jyuryo");

        $qb->leftJoin('m_bumon', 't_zaiko.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->addSelect('bumon_nm');

        $qb->leftJoin('m_ninusi', 't_zaiko.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->addSelect('ninusi1_nm');

        $qb->leftJoin('m_soko_hinmei', function ($j) {
            $j->on('t_zaiko.ninusi_cd', 'm_soko_hinmei.ninusi_cd');
            $j->on('t_zaiko.hinmei_cd', 'm_soko_hinmei.hinmei_cd');
        })->addSelect('hinmei_nm', 'kikaku');

        $qb->leftJoin("m_meisyo AS m_meisyo_case", function ($j) {
            $j->on('m_soko_hinmei.case_cd', 'm_meisyo_case.meisyo_cd');
            $j->where('m_meisyo_case.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
        })->addSelect('m_meisyo_case.meisyo_nm AS case_nm');

        $groupByHinmei = [
            't_zaiko.bumon_cd',
            't_zaiko.bumon_nm',
            't_zaiko.ninusi_cd',
            't_zaiko.ninusi1_nm',
            't_zaiko.hinmei_cd',
            't_zaiko.hinmei_nm',
            't_zaiko.case_nm',
            't_zaiko.kikaku',
        ];

        switch ($routeNm) {
            case "shouhin_ukebarai.exp.csv":
                $qb->addSelect(
                    'm_ninusi.ninusi_ryaku_nm',
                    'm_ninusi.ninusi2_nm',
                    't1.tani_cd',
                    'm_soko_hinmei.case_cd',
                    'm_soko_hinmei.irisu'
                );
                $groupByHinmei = array_merge($groupByHinmei, [
                    't_zaiko.ninusi_ryaku_nm',
                    't_zaiko.ninusi2_nm',
                    't_zaiko.tani_cd',
                    't_zaiko.case_cd',
                    't_zaiko.tani_nm',
                    't_zaiko.irisu'
                ], ($isGroupLot ? ['t_zaiko.lot1', 't_zaiko.lot2', 't_zaiko.lot3'] : [])
                );
                $qb->leftJoin("m_meisyo AS m_meisyo_tani", function ($j) {
                    $j->on('t1.tani_cd', 'm_meisyo_tani.meisyo_cd');
                    $j->where('m_meisyo_tani.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
                })->addSelect('m_meisyo_tani.meisyo_nm AS tani_nm');
                break;
        }

        if ($isGroupLot) {
            $qb->addSelect(DB::raw("CONCAT_WS(' ', t_zaiko.lot1, t_zaiko.lot2, t_zaiko.lot3) AS concat_lot"));
        } else {
            $qb = DB::query()->fromSub($qb , 't_zaiko')->select(
                array_merge($groupByHinmei, [
                    DB::raw("SUM(t_zaiko.zaiko_case_su) as zaiko_case_su"),
                    DB::raw("SUM(t_zaiko.zaiko_hasu) as zaiko_hasu"),
                    DB::raw("SUM(t_zaiko.zaiko_su) as zaiko_su"),
                    DB::raw("SUM(t_zaiko.zaiko_jyuryo) as zaiko_jyuryo"),
                    DB::raw("SUM(t_zaiko.in_case_su) as in_case_su"),
                    DB::raw("SUM(t_zaiko.in_hasu) as in_hasu"),
                    DB::raw("SUM(t_zaiko.in_su) as in_su"),
                    DB::raw("SUM(t_zaiko.in_jyuryo) as in_jyuryo"),
                    DB::raw("SUM(t_zaiko.out_case_su) as out_case_su"),
                    DB::raw("SUM(t_zaiko.out_hasu) as out_hasu"),
                    DB::raw("SUM(t_zaiko.out_su) as out_su"),
                    DB::raw("SUM(t_zaiko.out_jyuryo) as out_jyuryo"),
                    DB::raw("SUM(t_zaiko.zankou_case_su) as zankou_case_su"),
                    DB::raw("SUM(t_zaiko.zankou_hasu) as zankou_hasu"),
                    DB::raw("SUM(t_zaiko.zankou_su) as zankou_su"),
                    DB::raw("SUM(t_zaiko.zankou_jyuryo) as zankou_jyuryo"),
                ]))->groupBy($groupByHinmei);
        }

        $qb->orderBy('t_zaiko.bumon_cd');
        $qb->orderBy('t_zaiko.ninusi_cd');
        $qb->orderBy('t_zaiko.hinmei_cd');

        return $qb;
    }

    public function subQuerySum($request, $routeNm, $isGroupLot, $totalTwoTime = false)
    {
        $groupBy = [
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.soko_cd',
        ];
        if ($routeNm == 'shouhin_ukebarai.exp.csv') {
            $groupBy = array_merge($groupBy, ['t_nyusyuko_meisai.tani_cd']);
        }
        if ($isGroupLot) {
            $groupBy = array_merge($groupBy, ['t_nyusyuko_meisai.lot1', 't_nyusyuko_meisai.lot2', 't_nyusyuko_meisai.lot3']);
        }
        $qb = DB::query()->newQuery()
            ->select(array_merge($groupBy, [
                DB::raw("SUM(jyuryo) AS jyuryo"),
                // 1:入庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN t_nyusyuko_meisai.su ELSE 0 END) AS in_su"),
                // 2:出庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN t_nyusyuko_meisai.su ELSE 0 END) AS out_su"),
                // 4
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '4' THEN t_nyusyuko_meisai.su ELSE 0 END) AS exist_su"),
                // 5
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '5' THEN t_nyusyuko_meisai.su ELSE 0 END) AS moving_su"),
            ]))
            ->from('t_nyusyuko_head')
            ->leftJoin('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no')
            ->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(configParam('NYUSYUKO_KBN_SUPPORT')));
        $qb->groupBy($groupBy);
        if ($request->filled('kisan_dt_from')) {
            $qb->where('t_nyusyuko_head.kisan_dt', '>=', $request['kisan_dt_from']);
        }
        if ($totalTwoTime) {
            if ($request->filled('kisan_dt_to')) {
                $qb->where('t_nyusyuko_head.kisan_dt', '<=', $request['kisan_dt_to']);
            }
        }
        return $qb;
    }

}