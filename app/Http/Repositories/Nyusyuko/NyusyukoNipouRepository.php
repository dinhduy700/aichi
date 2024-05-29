<?php


namespace App\Http\Repositories\Nyusyuko;


use App\Models\TNyusyukoHead;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NyusyukoNipouRepository
{
    const EXP_PRINT_CSV_HEADER = '1';
    const M_USER_PG_FUNCTION__PG_NAME = 'nyusyuko_nipou';

    public function getOptionOpts() {
        return [
            $this::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力'],
        ];
    }

    public function handleInitValues($func, Request $request = null)
    {
        $mapping = [
            'exp.option' => ['nm' => 'choice1', 'type' => 'char']
        ];
        $attributes = [
            'user_cd' => Auth::id(),
            'pg_nm' => self::M_USER_PG_FUNCTION__PG_NAME,
            'function' => 'init'
        ];
        switch ($func) {
            case 'get':
                $first = DB::table('m_user_pg_function')->where($attributes)->first();
                $values = [];
                if ($first) {
                    foreach ($mapping as $key => $fields) {
                        $field = "{$fields['nm']}_{$fields['type']}";
                        $values[$key] = json_decode($first->$field);
                    }
                }
                $values['exp.kijyun_dt'] = DB::table('t_zaiko_kijyun')->max('kijyun_dt');
                return $values;

            case 'set':
                $values = [];
                foreach ($mapping as $key => $fields) {
                    $values["{$fields['nm']}_nm"] = $key;
                    $values["{$fields['nm']}_{$fields['type']}"] = json_encode(data_get($request->all(), $key));
                }
                DB::table('m_user_pg_function')->updateOrInsert($attributes, $values);
        }
    }

    private function qbBase($request)
    {
        $qb = DB::query()->newQuery()->from('t_nyusyuko_head')
            ->join('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no');
        $qb->where('t_nyusyuko_head.kisan_dt', '>=', $request->kijyun_dt);

        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd' => 't_nyusyuko_head.bumon_cd',
                'ninusi_cd' => 't_nyusyuko_head.ninusi_cd',
                'hinmei_cd' => 't_nyusyuko_meisai.hinmei_cd',
            ];
            foreach ($fromTo as $reqKey => $field) {
                if (is_null($value)) continue;
                if ($key == "{$reqKey}_from") $qb->where($field, '>=', $value);
                if ($key == "{$reqKey}_to") $qb->where($field, '<=', $value);
            }
        }

        return $qb;
    }

    /**
     * 前日
     * @param $kijyunDt
     * @return Builder
     */
    private function qbLastDt($request)
    {
        $curtDt = $request->kijyun_dt;
        $lastDt = Carbon::parse($curtDt)->addDays(-1)->format('Y-m-d');

        $groupByZaikoKijyun = ['bumon_cd', 'ninusi_cd', 'hinmei_cd', 'location'];
        $maxTZaikoKijyun = DB::table('t_zaiko_kijyun')
            ->select($groupByZaikoKijyun)
            ->selectRaw('MAX(kijyun_dt) AS max_kijyun_dt')
            ->groupBy($groupByZaikoKijyun)
            ->where('kijyun_dt', '<=', $lastDt);

        $groupBy = [
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.location',
            'max_zaiko_kijyun.max_kijyun_dt',
        ];

        $base = $this->qbBase($request)->select([
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.location',
        ])->distinct();
        $nyusyoko = TNyusyukoHead::query()->joinMeisai();
        $nyusyoko->joinSub($base, 'base', function ($j) {
            $j->on('t_nyusyuko_head.bumon_cd', '=', 'base.bumon_cd');
            $j->on('t_nyusyuko_head.ninusi_cd', '=', 'base.ninusi_cd');
            $j->on('t_nyusyuko_meisai.hinmei_cd', '=', 'base.hinmei_cd');
            $j->on('t_nyusyuko_meisai.location', '=', 'base.location');
        });

        $nyusyoko->select(array_merge($groupBy, [
                // 1:入庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.case_su, 0) ELSE 0 END) AS in_case_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.hasu, 0) ELSE 0 END) AS in_hasu_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.su, 0) ELSE 0 END) AS in_su"),
                // 2:出庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.case_su, 0) ELSE 0 END) AS out_case_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.hasu, 0) ELSE 0 END) AS out_hasu_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.su, 0) ELSE 0 END) AS out_su"),
                // 4:
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '4' THEN COALESCE(t_nyusyuko_meisai.su, 0) ELSE 0 END) AS kbn4_su"),
                // 5:
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '5' THEN COALESCE(t_nyusyuko_meisai.su, 0) ELSE 0 END) AS kbn5_su"),
            ]))
            ->leftJoinSub($maxTZaikoKijyun, 'max_zaiko_kijyun', function ($j) {
                $j->on('t_nyusyuko_head.bumon_cd', '=', 'max_zaiko_kijyun.bumon_cd');
                $j->on('t_nyusyuko_head.ninusi_cd', '=', 'max_zaiko_kijyun.ninusi_cd');
                $j->on('t_nyusyuko_meisai.hinmei_cd', '=', 'max_zaiko_kijyun.hinmei_cd');
                $j->on('t_nyusyuko_meisai.location', '=', 'max_zaiko_kijyun.location');
            })
            ->where(function($q) {
                $q->whereNull('max_zaiko_kijyun.max_kijyun_dt');
                $q->orWhere('t_nyusyuko_head.kisan_dt', '>', DB::raw('max_zaiko_kijyun.max_kijyun_dt'));
            })
            ->where('t_nyusyuko_head.kisan_dt', '<=', $lastDt)
            ->groupBy($groupBy);


        $qb = DB::query()->fromSub($nyusyoko, 'nyusyuko')
            ->leftJoin('t_zaiko_kijyun', function ($j) {
                $j->on('nyusyuko.max_kijyun_dt', '=', 't_zaiko_kijyun.kijyun_dt');
                $j->on('nyusyuko.bumon_cd', '=', 't_zaiko_kijyun.bumon_cd');
                $j->on('nyusyuko.ninusi_cd', '=', 't_zaiko_kijyun.ninusi_cd');
                $j->on('nyusyuko.hinmei_cd', '=', 't_zaiko_kijyun.hinmei_cd');
                $j->on('nyusyuko.location', '=', 't_zaiko_kijyun.location');
            })
            ->leftJoin('m_soko_hinmei', function ($j) {
                $j->on('nyusyuko.ninusi_cd', '=', 'm_soko_hinmei.ninusi_cd');
                $j->on('nyusyuko.hinmei_cd', '=', 'm_soko_hinmei.hinmei_cd');
            })
            ->select([
                'nyusyuko.bumon_cd',
                'nyusyuko.ninusi_cd',
                'nyusyuko.hinmei_cd',
                'nyusyuko.location'
            ]);
        $bindings = [$lastDt];
        $reCalSu = "(COALESCE(t_zaiko_kijyun.zaiko_all_su, 0) + in_su - out_su + kbn4_su + kbn5_su)";
        $qb->selectRaw("CASE WHEN nyusyuko.max_kijyun_dt = ? THEN t_zaiko_kijyun.zaiko_all_su"
            . " ELSE {$reCalSu}"
            . " END AS zaiko_all_su", $bindings);
        $qb->selectRaw("CASE WHEN nyusyuko.max_kijyun_dt = ? THEN t_zaiko_kijyun.case_su"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN DIV({$reCalSu}, m_soko_hinmei.irisu)"
            . " ELSE {$reCalSu}"
            . " END AS case_su", $bindings);
        $qb->selectRaw("CASE WHEN nyusyuko.max_kijyun_dt = ? THEN t_zaiko_kijyun.hasu_su"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL AND m_soko_hinmei.irisu != 0 THEN MOD({$reCalSu}, m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS hasu_su", $bindings);

        return $qb;
    }

    /**
     * 当日
     * @param $request
     * @return mixed
     */
    private function qbCurtDt($request)
    {
        $curtDt = $request->kijyun_dt;
        $groupBy = [
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.location',
        ];
        //nyusyuko_kbn	入出庫区分, 1:入庫、2:出庫
        $qb = $this->qbBase($request);
        $qb->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(config('params.NYUSYUKO_KBN')));
        return $qb
            ->select(array_merge($groupBy, [
                // 1:入庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.case_su, 0) ELSE 0 END) AS in_case_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.hasu, 0) ELSE 0 END) AS in_hasu_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.su, 0) ELSE 0 END) AS in_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '1' THEN COALESCE(t_nyusyuko_meisai.jyuryo, 0) ELSE 0 END) AS in_jyuryo"),
                // 2:出庫
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.case_su, 0) ELSE 0 END) AS out_case_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.hasu, 0) ELSE 0 END) AS out_hasu_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.su, 0) ELSE 0 END) AS out_su"),
                DB::raw("SUM(CASE WHEN t_nyusyuko_head.nyusyuko_kbn = '2' THEN COALESCE(t_nyusyuko_meisai.jyuryo, 0) ELSE 0 END) AS out_jyuryo"),
            ]))
            ->groupBy($groupBy)
            ->where('t_nyusyuko_head.kisan_dt', $curtDt);
    }

    public function qbExport($request, $routeNm = null)
    {
        $kijyunDt = $request->kijyun_dt;
        if (empty($kijyunDt)) return null;

        $qb = $this->qbBase($request);
        $qb->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(config('params.NYUSYUKO_KBN')));

        $qb->select([
            't_nyusyuko_head.bumon_cd',
            't_nyusyuko_head.ninusi_cd',
            't_nyusyuko_meisai.hinmei_cd',
            't_nyusyuko_meisai.soko_cd',
            't_nyusyuko_meisai.location',//ロケーション
        ]);

        // 部門
        $qb->leftJoin('m_bumon', 't_nyusyuko_head.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->addSelect('bumon_nm');

        // 荷主
        $qb->leftJoin('m_ninusi', 't_nyusyuko_head.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->addSelect(['m_ninusi.ninusi_ryaku_nm', 'm_ninusi.ninusi1_nm', 'm_ninusi.ninusi2_nm']);

        // 商品名,
        // 規格・ロット
        $qb->leftJoin('m_soko_hinmei', function ($j) {
            $j->on('t_nyusyuko_head.ninusi_cd', '=', 'm_soko_hinmei.ninusi_cd');
            $j->on('t_nyusyuko_meisai.hinmei_cd', '=', 'm_soko_hinmei.hinmei_cd');
        })->addSelect(['hinmei_nm', 'kikaku', 'case_cd', 'irisu']);
        // Excel.単位 // ケース単位
        $qb->leftJoin("m_meisyo AS m_meisyo_case", function ($j){
            $j->on('m_soko_hinmei.case_cd', '=', 'm_meisyo_case.meisyo_cd');
            $j->where('m_meisyo_case.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        })->addSelect('m_meisyo_case.meisyo_nm AS case_nm');

        // 前日残高
        $lastJuryo = "(m_soko_hinmei.bara_tani_juryo * t_last.zaiko_all_su)";
        $qb->leftJoinSub($this->qbLastDt($request), 't_last', function ($j) {
            $j->on('t_nyusyuko_head.bumon_cd', '=', 't_last.bumon_cd');
            $j->on('t_nyusyuko_head.ninusi_cd', '=', 't_last.ninusi_cd');
            $j->on('t_nyusyuko_meisai.hinmei_cd', '=', 't_last.hinmei_cd');
            $j->on('t_nyusyuko_meisai.location', '=', 't_last.location');
        })->addSelect([
            't_last.case_su AS last_case_su',//ケース
            't_last.hasu_su AS last_hasu_su',//端数
            't_last.zaiko_all_su AS last_su',//総数
            DB::raw("{$lastJuryo} AS last_juryo"),//重量
        ]);

        //当日
        $inJuryo = "(t_curt.in_jyuryo)";
        $outJuryo = "(t_curt.out_jyuryo)";
        $qb->leftJoinSub($this->qbCurtDt($request), 't_curt', function ($j) {
            $j->on('t_nyusyuko_head.bumon_cd', '=', 't_curt.bumon_cd');
            $j->on('t_nyusyuko_head.ninusi_cd', '=', 't_curt.ninusi_cd');
            $j->on('t_nyusyuko_meisai.hinmei_cd', '=', 't_curt.hinmei_cd');
            $j->on('t_nyusyuko_meisai.location', '=', 't_curt.location');
        })->addSelect([
            //当日入庫
            't_curt.in_case_su',//ケース
            't_curt.in_hasu_su',//端数
            't_curt.in_su',//総数
            DB::raw("{$inJuryo} AS in_juryo"),//重量
            //当日出庫
            't_curt.out_case_su',//ケース
            't_curt.out_hasu_su',//端数
            't_curt.out_su',//総数
            DB::raw("{$outJuryo} AS out_juryo"),//重量
        ]);

        //当日残高 = 前日残高　＋　当日入庫　-　当日出庫
        //　　ケース/端数				ケース数	前日残高.case_su + 当日入庫.case_su - 当日出庫.case_su
        //                                  前日残高.hasu + 当日入庫.hasu - 当日出庫.hasu
        $curtCase = "(COALESCE(t_last.case_su, 0) + COALESCE(in_case_su, 0) - COALESCE(out_case_su, 0))";
        $curtHasu = "(COALESCE(t_last.hasu_su, 0) + COALESCE(in_hasu_su, 0) - COALESCE(out_hasu_su, 0))";
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL THEN {$curtCase} + DIV($curtHasu, m_soko_hinmei.irisu)"
            . " ELSE {$curtCase} + {$curtHasu}"
            . " END AS curt_case_su");
        $qb->selectRaw("CASE"
            . " WHEN m_soko_hinmei.irisu IS NOT NULL THEN MOD(ABS($curtHasu),m_soko_hinmei.irisu)"
            . " ELSE 0"
            . " END AS curt_hasu_su");
        //　　総数              数量	前日残高.su + 当日入庫.su - 当日出庫.su
        $qb->selectRaw("(COALESCE(t_last.zaiko_all_su, 0) + COALESCE(in_su, 0) - COALESCE(out_su, 0)) AS curt_su");
        //　　重量              重量/m3	前日残高.jyuryo + 当日入庫.jyuryo - 当日出庫.jyuryo
        $qb->selectRaw("(COALESCE({$lastJuryo}, 0) + COALESCE({$inJuryo}, 0) - COALESCE({$outJuryo}, 0)) AS curt_jyuryo");

        switch ($routeNm) {
            case 'nyusyuko.nipou.nipouCsv':
                $qb->addSelect([
                    't_nyusyuko_meisai.lot1',
                    't_nyusyuko_meisai.lot2',
                    't_nyusyuko_meisai.lot3',
                    't_nyusyuko_meisai.tani_cd',
                ]);

                // 単位
                $qb->leftJoin("m_meisyo AS m_meisyo_tani", function ($j) {
                    $j->on("t_nyusyuko_meisai.tani_cd", '=', "m_meisyo_tani.meisyo_cd");
                    $j->where("m_meisyo_tani.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
                })->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");

                $qb->leftJoin('m_soko', function ($j){
                    $j->on('t_nyusyuko_head.bumon_cd', '=', 'm_soko.bumon_cd');
                    $j->on('t_nyusyuko_meisai.soko_cd', '=', 'm_soko.soko_cd');
                })->addSelect('m_soko.soko_nm');
                break;
        }

        $qb->distinct();
        return $qb;
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);
        return $qb;
    }
}
