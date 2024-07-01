<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UketsukeHaraichoRepository
{
    const EXP_PRINT_CSV_HEADER = '1';

    const EXP_PRINT_GROUP = '2';

    const EXP_PRINT_AMOUNT_AND_INVENTORY = '3';

    public function getOptionOpts()
    {
        return [
            $this::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力（M）'],
            $this::EXP_PRINT_GROUP => ['text' => 'ロット別に出力'],
            $this::EXP_PRINT_AMOUNT_AND_INVENTORY => ['text' => '入出庫発生分のみ出力する'],
        ];
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);
        return $qb;
    }

    private function qbBase($request, $isGroupLot)
    {
        $groups = [
            't_zaiko.bumon_cd',
            't_zaiko.ninusi_cd',
            't_zaiko.soko_cd',
            't_zaiko.hinmei_cd'
        ];

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
        $qb = DB::query()->fromSub($tZaiko, 't_zaiko')->select('t_zaiko.*');
        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd' => 't_zaiko.bumon_cd',
                'ninusi_cd' => 't_zaiko.ninusi_cd',
                'hinmei_cd' => 't_zaiko.hinmei_cd'
            ];
            foreach ($fromTo as $reqKey => $field) {
                if (is_null($value)) continue;
                if ($key == "{$reqKey}_from") $qb->where($field, '>=', $value);
                if ($key == "{$reqKey}_to") $qb->where($field, '<=', $value);
            }
        }
        return $qb;
    }

    public function qbExport($request, $routeNm = null)
    {
        $isGroupLot = true;
        if (!in_array(self::EXP_PRINT_GROUP, data_get($request, 'option', []))) {
            $isGroupLot = false;
        }
        $qb = $this->qbBase($request, $isGroupLot);
        $subQueryHeadMeisai = $this->subQueryHeadMeisai($request, $routeNm, $isGroupLot);

        if (in_array(self::EXP_PRINT_AMOUNT_AND_INVENTORY, data_get($request, 'option', []))) {
            $qb->joinSub($subQueryHeadMeisai, 't1', function ($join) use ($isGroupLot) {
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
        } else {
            $qb->leftJoinSub($subQueryHeadMeisai, 't1', function ($join) use ($isGroupLot) {
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
        }

        $qb->addSelect(
            "t1.nyusyuko_kbn",
            'm_soko_hinmei.bara_tani_juryo',
            't1.kisan_dt',
            't1.kisan_dt as hidzuke_dt',
            't1.location',
            't1.todokesaki_nm',
            DB::raw("COALESCE(t1.in_jyuryo,0) as in_jyuryo"),
            DB::raw("COALESCE(CASE WHEN t1.out_jyuryo < 0 THEN t1.out_jyuryo * (-1) ELSE t1.out_jyuryo END, 0) as out_jyuryo"),
            'nyusyuko_den_no',
            DB::raw('CASE WHEN m_soko_hinmei.irisu IS NULL OR m_soko_hinmei.irisu = 0 THEN 1 ELSE m_soko_hinmei.irisu END'),
            DB::raw("NULL AS no_printing1")
        );
        //入庫数
        $inSu = "(COALESCE(t1.in_su,0) + COALESCE(t1.exist_su,0) + (CASE WHEN t1.moving_su > 0 THEN t1.moving_su ELSE 0 END))";
        $qb->selectRaw("{$inSu} as in_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$inSu}, m_soko_hinmei.irisu)"
            . " ELSE {$inSu}"
            . " END AS in_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$inSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS in_hasu");

        //出庫数
        $outSu = "(COALESCE(t1.out_su,0) + (CASE WHEN t1.moving_su < 0 THEN t1.moving_su * (-1) ELSE 0 END))";
        $qb->selectRaw("{$outSu} as out_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$outSu}, m_soko_hinmei.irisu)"
            . " ELSE {$outSu}"
            . " END AS out_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$outSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS out_hasu");

        $qb->leftJoin('m_bumon', 't_zaiko.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->addSelect('bumon_nm');

        $qb->leftJoin('m_ninusi', 't_zaiko.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->addSelect('ninusi1_nm');

        $qb->leftJoin('m_soko_hinmei', function ($j) {
            $j->on('t_zaiko.ninusi_cd', 'm_soko_hinmei.ninusi_cd');
            $j->on('t_zaiko.hinmei_cd', 'm_soko_hinmei.hinmei_cd');
        })->addSelect('hinmei_nm');

        switch ($routeNm) {
            case "uketsuke_haraicho.exp.csv":
                $qb->addSelect(
                    'm_ninusi.ninusi_ryaku_nm',
                    'm_ninusi.ninusi2_nm',
                    'm_soko_hinmei.kikaku',
                    "t1.tani_cd",
                    'm_soko_hinmei.case_cd',
                    DB::raw("NULL AS no_printing2")
                );
                $qb->leftJoin("m_meisyo AS m_meisyo_tani", function ($j) {
                    $j->on('t1.tani_cd', 'm_meisyo_tani.meisyo_cd');
                    $j->where('m_meisyo_tani.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
                })->addSelect('m_meisyo_tani.meisyo_nm AS tani_nm');

                $qb->leftJoin("m_meisyo AS m_meisyo_case", function ($j) {
                    $j->on('m_soko_hinmei.case_cd', 'm_meisyo_case.meisyo_cd');
                    $j->where('m_meisyo_case.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
                })->addSelect('m_meisyo_case.meisyo_nm AS case_nm');

                $qb->leftJoin('m_soko', function ($j) {
                    $j->on('t_zaiko.bumon_cd', 'm_soko.bumon_cd');
                    $j->on('t_zaiko.soko_cd', 'm_soko.soko_cd');
                })->addSelect('soko_nm');
                break;
        }
        $qb->orderBy('t_zaiko.bumon_cd');
        $qb->orderBy('t_zaiko.ninusi_cd');
        $qb->orderBy('t_zaiko.hinmei_cd');
        $qb->orderBy('t1.kisan_dt');
        $qb->orderBy('t1.soko_cd');
        $qb->orderBy('t1.location');

        $qbSubByKisanDt = $this->getSuByKisanDt($request, $isGroupLot);
        $qb->leftJoinSub($qbSubByKisanDt, 'dt', function ($join) use ($isGroupLot) {
            $join->on('t_zaiko.bumon_cd', 'dt.bumon_cd')
                ->on('t_zaiko.ninusi_cd', 'dt.ninusi_cd')
                ->on('t_zaiko.hinmei_cd', 'dt.hinmei_cd');
            if ($isGroupLot) {
                $join->on(function ($query) {
                    $query->on('t_zaiko.lot1', 'dt.lot1')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot1')
                                ->whereNull('dt.lot1');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot2', 'dt.lot2')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot2')
                                ->whereNull('dt.lot2');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot3', 'dt.lot3')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot3')
                                ->whereNull('dt.lot3');
                        });
                });
            }
        });
        //繰越数
        $qb->selectRaw('dt.zaiko_su');
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV(dt.zaiko_su, m_soko_hinmei.irisu)"
            . " ELSE dt.zaiko_su"
            . " END AS zaiko_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD(dt.zaiko_su, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS zaiko_hasu");
        $qb->selectRaw("(dt.zaiko_su * COALESCE(m_soko_hinmei.bara_tani_juryo, 0)) AS zaiko_jyuryo");
        return $qb;
    }

    public function subQueryHeadMeisai($request, $routeNm, $isGroupLot)
    {
        $groupBy = [
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.soko_cd',
            't_nyusyuko_head.nyusyuko_den_no',
            't_nyusyuko_head.nyusyuko_kbn',
            't_nyusyuko_head.kisan_dt',
            't_nyusyuko_meisai.location',
            't_nyusyuko_head.todokesaki_nm',
        ];
        if ($routeNm == 'uketsuke_haraicho.exp.csv') {
            $groupBy = array_merge($groupBy, ['t_nyusyuko_meisai.tani_cd']);
        }
        if ($isGroupLot) {
            $groupBy = array_merge($groupBy, ['t_nyusyuko_meisai.lot1', 't_nyusyuko_meisai.lot2', 't_nyusyuko_meisai.lot3']);
        }
        $qb = DB::query()->newQuery()
            ->select(array_merge($groupBy, [
                // 1:入庫
                DB::raw("SUM(CASE 
                    WHEN t_nyusyuko_head.nyusyuko_kbn = '1' 
                    OR t_nyusyuko_head.nyusyuko_kbn = '4' 
                    OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su > 0)
                    THEN t_nyusyuko_meisai.jyuryo ELSE 0 END) AS in_jyuryo"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN t_nyusyuko_meisai.su ELSE 0 END) AS in_su"),
                // 2:出庫
                DB::raw("SUM(CASE 
                    WHEN t_nyusyuko_head.nyusyuko_kbn = '2' 
                    OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su < 0) 
                    THEN t_nyusyuko_meisai.jyuryo ELSE 0 END) AS out_jyuryo"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN t_nyusyuko_meisai.su ELSE 0 END) AS out_su"),
                // 4
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '4' THEN t_nyusyuko_meisai.su ELSE 0 END) AS exist_su"),
                // 5
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '5' THEN t_nyusyuko_meisai.su ELSE 0 END) AS moving_su"),
            ]))
            ->from('t_nyusyuko_head')
            ->leftJoin('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no')
            ->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(configParam('NYUSYUKO_KBN_SUPPORT')));
        if ($request->filled('kisan_dt_from')) {
            $qb->where('t_nyusyuko_head.kisan_dt', '>=', $request['kisan_dt_from']);
        }
        if ($request->filled('kisan_dt_to')) {
            $qb->where('t_nyusyuko_head.kisan_dt', '<=', $request['kisan_dt_to']);
        }
        $qb->groupBy($groupBy);
        return $qb;
    }

    public function subQueryByKisan($isGroupLot, $request)
    {
        $groupBy = [
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.soko_cd'
        ];
        if ($isGroupLot) {
            $groupBy = array_merge($groupBy, ['t_nyusyuko_meisai.lot1', 't_nyusyuko_meisai.lot2', 't_nyusyuko_meisai.lot3']);
        }
        $qb = DB::query()->newQuery()
            ->select(array_merge($groupBy, [
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
        if ($request->filled('kisan_dt_from')) {
            $qb->where('t_nyusyuko_head.kisan_dt', '>=', $request['kisan_dt_from']);
        }
        $qb->groupBy($groupBy);
        return $qb;
    }

    public function getSuByKisanDt($request, $isGroupLot)
    {
        $qb = $this->qbBase($request, $isGroupLot);
        $subQueryByKisan = $this->subQueryByKisan($isGroupLot,$request);
        $qb->leftJoinSub($subQueryByKisan, 'hm', function ($join) use ($isGroupLot) {
            $join->on('t_zaiko.bumon_cd', 'hm.bumon_cd')
                ->on('t_zaiko.ninusi_cd', 'hm.ninusi_cd')
                ->on('t_zaiko.hinmei_cd', 'hm.hinmei_cd')
                ->on('t_zaiko.soko_cd', 'hm.soko_cd');
            if ($isGroupLot) {
                $join->on(function ($query) {
                    $query->on('t_zaiko.lot1', 'hm.lot1')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot1')
                                ->whereNull('hm.lot1');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot2', 'hm.lot2')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot2')
                                ->whereNull('hm.lot2');
                        });
                })->on(function ($query) {
                    $query->on('t_zaiko.lot3', 'hm.lot3')
                        ->orWhere(function ($query) {
                            $query->whereNull('t_zaiko.lot3')
                                ->whereNull('hm.lot3');
                        });
                });
            }
        });
        $qb->selectRaw("(
            COALESCE ( t_zaiko.su, 0 ) - 
            COALESCE ( hm.in_su, 0 ) + 
            COALESCE ( hm.out_su, 0 ) - 
            COALESCE ( hm.exist_su, 0 ) - 
            COALESCE ( hm.moving_su, 0 )) as zaiko_su");
        $groupBy = [
            'tz.bumon_cd',
            'tz.ninusi_cd',
            'tz.hinmei_cd'
        ];
        if ($isGroupLot) {
            $groupBy = array_merge($groupBy, ['tz.lot1', 'tz.lot2', 'tz.lot3']);
        }
        return DB::query()->select($groupBy)->selectRaw("SUM(tz.zaiko_su) as zaiko_su")->fromSub($qb, 'tz')->groupBy($groupBy);
    }
}