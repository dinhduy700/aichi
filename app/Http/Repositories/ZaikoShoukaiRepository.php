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
        $qb = $this->qbBySearchZaikoShoukai($request);

        $qb->select(
            DB::raw('ROW_NUMBER() OVER () as index'),
            't_zaiko.bumon_cd',
            't_zaiko.ninusi_cd',
            't_zaiko.hinmei_cd',
            't_zaiko.soko_cd',
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
        $groupBy = [
            "t_zaiko.bumon_cd",
            "t_zaiko.ninusi_cd",
            "t_zaiko.hinmei_cd",
            "t_zaiko.soko_cd",
            "m_soko_hinmei.hinmei_nm",
            "m_soko_hinmei.kikaku",
            "irisu",
            "m_soko_hinmei.bara_tani_juryo",
            "case_meisyo.meisyo_nm",
            "bara_tani_meisyo.meisyo_nm"
        ];
        if ($request->filled('option')) {
            $options = array_values($request['option']);
            if (in_array($this::SHOW_LIST_LOT, $options)) {
                $qb->addSelect(
                    't_zaiko.lot1',
                    't_zaiko.lot2',
                    't_zaiko.lot3'
                );
                $qb->selectRaw("t_zaiko.su - 
                    {$this->sumStringQuery('1', true)} + 
                    {$this->sumStringQuery('2', true)} 
                    as zaiko_su", [$request->search_kisan_dt, $request->search_kisan_dt]
                );
            } else {
                $qb->selectRaw("SUM(t_zaiko.su - 
                    {$this->sumStringQuery('1', true)} + 
                    {$this->sumStringQuery('2', true)}
                    ) as zaiko_su ", [$request->search_kisan_dt, $request->search_kisan_dt]
                );
                $qb->groupBy($groupBy);
            }

            if (!in_array($this::SHOW_OUT_OF_STOCK, $options)) {
                $qb->where("t_zaiko.su", ">", 0);
            }
        } else {
            $qb->where("t_zaiko.su", ">", 0);
            $qb->selectRaw("SUM(t_zaiko.su - 
                    {$this->sumStringQuery('1', true)} + 
                    {$this->sumStringQuery('2', true)}
                    ) as zaiko_su ", [$request->search_kisan_dt, $request->search_kisan_dt]
            );
            $qb->groupBy($groupBy);
        }
        $list = $qb;
        $total = $list->count();

        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function qbBySearchZaikoShoukai($request)
    {
        $qb = DB::query()->newQuery()->from('t_zaiko');
        $qb->leftJoin('m_bumon', 't_zaiko.bumon_cd', '=', 'm_bumon.bumon_cd');
        $qb->leftJoin('m_ninusi', 't_zaiko.ninusi_cd', '=', 'm_ninusi.ninusi_cd');

        $qb->leftJoin('m_soko_hinmei', function ($join) {
            $join->on('t_zaiko.hinmei_cd', 'm_soko_hinmei.hinmei_cd')
                ->on('t_zaiko.ninusi_cd', 'm_soko_hinmei.ninusi_cd');
        });
        $qb->leftJoin('m_soko', function ($j) {
            $j->on('t_zaiko.bumon_cd', 'm_soko.bumon_cd');
            $j->on('t_zaiko.soko_cd', 'm_soko.soko_cd');
        });
        if ($request->filled('search_bumon_cd')) {
            $qb->where('t_zaiko.bumon_cd', $request['search_bumon_cd']);
        }
        if ($request->filled('search_bumon_nm')) {
            $qb->where('m_bumon.bumon_nm', $request['search_bumon_nm']);
        }
        if ($request->filled('search_ninusi_cd')) {
            $qb->where('t_zaiko.ninusi_cd', $request['search_ninusi_cd']);
        }
        if ($request->filled('search_ninusi_ryaku_nm')) {
            $qb->where('m_ninusi.ninusi_ryaku_nm', $request['search_ninusi_ryaku_nm']);
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
                        THEN t_nyusyuko_meisai.case_su 
                        ELSE 0 
                    END as out_case_su
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '2' 
                        OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su < 0) 
                        THEN t_nyusyuko_meisai.hasu 
                        ELSE 0 
                    END as out_hasu
            "),
            DB::raw("CASE 
                        WHEN t_nyusyuko_head.nyusyuko_kbn = '2' 
                        OR (t_nyusyuko_head.nyusyuko_kbn = '5' AND t_nyusyuko_meisai.su < 0) 
                        THEN t_nyusyuko_meisai.su 
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

    public function sumStringQuery($nyusyukoKbn, $isWhereLot = false, $isWhereKisanDtTo = false)
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
                    AND t_nyusyuko_head.kisan_dt >= ?"
            . ($isWhereKisanDtTo ? " AND t_nyusyuko_head.kisan_dt <= ?" : '') . "
             ), 0)";
    }
}
