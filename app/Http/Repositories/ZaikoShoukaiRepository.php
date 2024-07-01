<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;

class ZaikoShoukaiRepository
{
    const SHOW_LIST_LOT = 1;
    const SHOW_OUT_OF_STOCK = 2;
    const SEARCH_HINMEI_NM_START_WITH = 1;
    const SEARCH_HINMEI_NM_ANY = 2;

    const LOT_KANRI_KBN_1 = '1';

    const LOT_KANRI_KBN_2 = '2';

    const LOT_KANRI_KBN_3 = '3';

    public function getSearchOpts()
    {
        return [
            $this::SHOW_LIST_LOT => ['text' => 'ロット別に表示'],
            $this::SHOW_OUT_OF_STOCK => ['text' => '在庫無しも表示'],
        ];
    }

    public function getSearchDrds()
    {
        return [
            $this::SEARCH_HINMEI_NM_START_WITH => ['text' => 'で始まる'],
            $this::SEARCH_HINMEI_NM_ANY => ['text' => 'を含む'],
        ];
    }

    public function getConfNyusyukoKbn()
    {
        return configParam('options.t_nyusyuko_head.nyusyuko_kbn');
    }

    public function getListDataZaikoShoukai($request)
    {
        $isGroupLot = true;
        if (!in_array(self::SHOW_LIST_LOT, data_get($request, 'option', []))) {
            $isGroupLot = false;
        }
        $qb = $this->qbBySearchZaikoShoukai($request, $isGroupLot);
        $qb->addSelect(
            'm_soko_hinmei.hinmei_nm',
            'm_soko_hinmei.kikaku',
            DB::raw("CASE WHEN m_soko_hinmei.irisu IS NULL OR m_soko_hinmei.irisu = 0 THEN 1 ELSE m_soko_hinmei.irisu END AS irisu"),
            'm_soko_hinmei.bara_tani_juryo'
        );
        $qb->leftJoin('m_meisyo as case_meisyo', function ($join) {
            $join->on('case_meisyo.meisyo_cd', 'm_soko_hinmei.case_cd');
            $join->where('case_meisyo.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
        })->addSelect('case_meisyo.meisyo_nm as case_meisyo_nm');

        $qb->leftJoin('m_meisyo as bara_tani_meisyo', function ($join) {
            $join->on('bara_tani_meisyo.meisyo_cd', 'm_soko_hinmei.bara_tani');
            $join->where('bara_tani_meisyo.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
        })->addSelect('bara_tani_meisyo.meisyo_nm as bara_tani_meisyo_nm');
        if ($isGroupLot) {
            $qb->selectRaw("
                t_zaiko.su - 
                {$this->sumStringQuery('1', true)} + 
                {$this->sumStringQuery('2', true)} -
                {$this->sumStringQuery('4', true)} -
                {$this->sumStringQuery('5', true)} 
                as zaiko_su", [
                    $request->search_kisan_dt,
                    $request->search_kisan_dt,
                    $request->search_kisan_dt,
                    $request->search_kisan_dt
                ]
            );
        } else {
            $qb->selectRaw("
                t_zaiko.su - 
                {$this->sumStringQuery('1')} + 
                {$this->sumStringQuery('2')} - 
                {$this->sumStringQuery('4')} -
                {$this->sumStringQuery('5')} 
                as zaiko_su", [
                    $request->search_kisan_dt,
                    $request->search_kisan_dt,
                    $request->search_kisan_dt,
                    $request->search_kisan_dt
                ]
            );
        }
        $groupNoneSoko = array_merge([
            "tz.bumon_cd",
            "tz.ninusi_cd",
            "tz.hinmei_cd",
            "tz.hinmei_nm",
            "tz.kikaku",
            "tz.irisu",
            "tz.bara_tani_juryo",
            "tz.case_meisyo_nm",
            "tz.bara_tani_meisyo_nm"
        ], ($isGroupLot ? ['tz.lot1', 'tz.lot2', 'tz.lot3'] : []));
        $qbGroup = DB::query()
            ->select($groupNoneSoko)
            ->selectRaw("ROW_NUMBER() OVER () as index")
            ->selectRaw("SUM(tz.zaiko_su) as zaiko_su")
            ->selectRaw("(SUM(tz.zaiko_su) * COALESCE(tz.bara_tani_juryo, 0)) AS zaiko_jyuryo")
            ->selectRaw("CASE"
                . " WHEN tz.irisu IS NOT NULL AND tz.irisu != 0 THEN DIV(SUM(tz.zaiko_su), tz.irisu)"
                . " ELSE SUM(tz.zaiko_su)"
                . " END AS zaiko_case_su")
            ->selectRaw("CASE"
                . " WHEN tz.irisu IS NOT NULL AND tz.irisu != 0 THEN MOD(SUM(tz.zaiko_su), tz.irisu)"
                . " ELSE 0"
                . " END AS zaiko_hasu")
            ->fromSub($qb, 'tz')
            ->groupBy($groupNoneSoko);

        if (!in_array(self::SHOW_OUT_OF_STOCK, data_get($request, 'option', []))) {
            $qbGroup->havingRaw("SUM(tz.zaiko_su) > 0");
        }
        return $qbGroup;
    }

    public function qbBySearchZaikoShoukai($request, $isGroupLot)
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

        $qb->leftJoin('m_soko_hinmei', function ($join) {
            $join->on('t_zaiko.hinmei_cd', 'm_soko_hinmei.hinmei_cd')
                ->on('t_zaiko.ninusi_cd', 'm_soko_hinmei.ninusi_cd');
        });
        if ($request->filled('search_bumon_cd')) {
            $qb->where('t_zaiko.bumon_cd', $request['search_bumon_cd']);
        }
        if ($request->filled('search_ninusi_cd')) {
            $qb->where('t_zaiko.ninusi_cd', $request['search_ninusi_cd']);
        }
        if ($request->filled('search_soko_hinmei_cd_from')) {
            $qb->where('t_zaiko.hinmei_cd', '>=', $request['search_soko_hinmei_cd_from']);
        }
        if ($request->filled('search_soko_hinmei_cd_to')) {
            $qb->where('t_zaiko.hinmei_cd', "<=", $request['search_soko_hinmei_cd_to']);
        }
        if ($request->filled('search_kensaku_zyoken') && $request->filled('search_soko_hinmei_nm')) {

            $hinmeiNM = makeEscapeStr($request['search_soko_hinmei_nm']);
            switch ($request['search_kensaku_zyoken']) {
                case $this::SEARCH_HINMEI_NM_START_WITH:
                    $hinmeiNM = $hinmeiNM . '%';
                    break;
                case $this::SEARCH_HINMEI_NM_ANY:
                    $hinmeiNM = '%' . $hinmeiNM . '%';
                    break;
            }
            $qb->where('m_soko_hinmei.hinmei_nm', "ILIKE", $hinmeiNM);
        }
        if ($request->filled('search_soko_cd_from')) {
            $qb->where('t_zaiko.soko_cd', '>=', $request['search_soko_cd_from']);
        }
        if ($request->filled('search_soko_cd_to')) {
            $qb->where('t_zaiko.soko_cd', '<=', $request['search_soko_cd_to']);
        }
        $qb->orderBy('t_zaiko.hinmei_cd');
        return $qb;
    }

    public function getListDataUkebaraiShoukai($request)
    {
        $qb = $this->qbBySearchUkebaraiShoukai($request);
        $qb->select(
            DB::raw("TO_CHAR(t_nyusyuko_head.kisan_dt, 'YYYY/MM/DD') AS kisan_dt"),
            't_nyusyuko_head.nyusyuko_kbn',
            DB::raw("NULL AS uchiwake"),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '1' 
                          OR t_nyusyuko_head.nyusyuko_kbn = '4' 
                          OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su > 0) 
                        THEN t_nyusyuko_meisai.case_su
                        ELSE 0 
                    END as in_case_su
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '1' 
                          OR t_nyusyuko_head.nyusyuko_kbn = '4' 
                          OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su > 0) 
                        THEN t_nyusyuko_meisai.hasu
                        ELSE 0 
                    END as in_hasu
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '1' 
                          OR t_nyusyuko_head.nyusyuko_kbn = '4' 
                          OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su > 0) 
                        THEN t_nyusyuko_meisai.su
                        ELSE 0 
                    END as in_su
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '2' 
                        OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su < 0) 
                        THEN ABS(t_nyusyuko_meisai.case_su) 
                        ELSE 0 
                    END as out_case_su
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '2' 
                        OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su < 0) 
                        THEN ABS(t_nyusyuko_meisai.hasu)
                        ELSE 0 
                    END as out_hasu
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '2' 
                        OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su < 0) 
                        THEN ABS(t_nyusyuko_meisai.su)
                        ELSE 0 
                    END as out_su
            "),
            't_nyusyuko_meisai.nyusyuko_den_no',
            't_nyusyuko_head.todokesaki_nm',
            DB::raw("CONCAT(t_nyusyuko_meisai.soko_cd, '：', t_nyusyuko_meisai.location) AS soko_cd_location"),
            't_nyusyuko_meisai.biko',
            't_nyusyuko_meisai.lot1',
            't_nyusyuko_meisai.lot2',
            't_nyusyuko_meisai.lot3',
        );

        $list = $qb;
        $total = $list->count();
        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function qbBySearchUkebaraiShoukai($request)
    {
        $qb = DB::query()->newQuery()
            ->from('t_nyusyuko_head')
            ->join('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no');
        if ($request->filled('bumonCd')) {
            $qb->where('t_nyusyuko_head.bumon_cd', $request['bumonCd']);
        }
        if ($request->filled('ninusiCd')) {
            $qb->where('t_nyusyuko_head.ninusi_cd', $request['ninusiCd']);
        }
        if ($request->filled('hinmeiCd')) {
            $qb->where('t_nyusyuko_meisai.hinmei_cd', $request['hinmeiCd']);
        }
        if ($request->filled('search_kisan_dt_from')) {
            $qb->where('t_nyusyuko_head.kisan_dt', ">=", $request['search_kisan_dt_from']);
        }
        if ($request->filled('search_kisan_dt_to')) {
            $qb->where('t_nyusyuko_head.kisan_dt', "<=", $request['search_kisan_dt_to']);
        }

        $qb->orderBy('t_nyusyuko_head.kisan_dt');

        return $qb;
    }

    public function sumStringQuery($nyusyukoKbn, $isWhereLot = false)
    {
        return
            "COALESCE((
                SELECT
                    SUM(su)
                FROM
                    t_nyusyuko_head
                JOIN t_nyusyuko_meisai ON t_nyusyuko_head.nyusyuko_den_no = t_nyusyuko_meisai.nyusyuko_den_no
                WHERE
                    t_zaiko.bumon_cd = t_nyusyuko_head.bumon_cd
                    AND t_zaiko.ninusi_cd = t_nyusyuko_head.ninusi_cd
                    AND t_zaiko.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                    AND t_zaiko.soko_cd = t_nyusyuko_meisai.soko_cd "
            . ($isWhereLot ? "AND t_zaiko.lot1 = t_nyusyuko_meisai.lot1
                    AND t_zaiko.lot2 = t_nyusyuko_meisai.lot2
                    AND t_zaiko.lot3 = t_nyusyuko_meisai.lot3 " : '') . "
                    AND t_nyusyuko_head.nyusyuko_kbn = '$nyusyukoKbn'
                    AND t_nyusyuko_head.kisan_dt > ?), 0)";
    }
}
