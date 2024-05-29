<?php


namespace App\Http\Repositories\Seikyu;


use App\Http\Requests\Seikyu\SeikyuListRequest;
use App\Models\TSeikyu;
use App\Models\TUriage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SeikyuListRepository
{
    const EXP_PRINT_CSV_HEADER = 'M';

    public function getRawSelect($field, $alias = null)
    {
        $mikakuteiKin = "COALESCE(t_uriage.unchin_kin, 0)"
            . " + COALESCE(t_uriage.tyukei_kin, 0)"
            . " + COALESCE(t_uriage.tukoryo_kin, 0)"
            . " + COALESCE(t_uriage.nieki_kin, 0)"
            . " + COALESCE(t_uriage.syuka_kin, 0)"
            . " + COALESCE(t_uriage.tesuryo_kin, 0)";

        $alias = $alias ?? $field;
        switch ($field) {
            // 売上データ t_uriage
            case 'mikakutei_su':
                return DB::raw("COUNT(uriage_den_no) AS {$alias}");
            case 'sum_mikakutei_kin'://SUM(基本運賃+中継料+通行料等+荷役料+集荷料+手数料) của các dòng unchin_mikakutei_kbn = 1
                return DB::raw("SUM({$mikakuteiKin}) AS {$alias}");
            case 'mikakutei_kin'://基本運賃+中継料+通行料等+荷役料+集荷料+手数料
                return DB::raw("({$mikakuteiKin}) AS {$alias}");

            // 請求データ t_seikyu
            case 'nyukin_kin'://入金額
                return DB::raw("(COALESCE(t_seikyu.genkin_kin, 0) + COALESCE(t_seikyu.furikomi_kin, 0) "
                ."+ COALESCE(t_seikyu.furikomi_tesuryo_kin, 0) + COALESCE(t_seikyu.tegata_kin, 0) "
                ."+ COALESCE(t_seikyu.sousai_kin, 0) + COALESCE(t_seikyu.nebiki_kin, 0) "
                ."+ COALESCE(t_seikyu.sonota_nyu_kin, 0)) AS nyukin_kin");

            case 'nyukin_kin_5'://入金額
                return DB::raw("(COALESCE(t_seikyu.genkin_kin, 0) + COALESCE(t_seikyu.furikomi_kin, 0) "
                    ."+ COALESCE(t_seikyu.furikomi_tesuryo_kin, 0) + COALESCE(t_seikyu.tegata_kin, 0) "
                    ."+ COALESCE(t_seikyu.sonota_nyu_kin, 0)) AS {$alias}");
        }
        return null;
    }

    public function getListBuilder($request, $joinType = 'left')
    {
        $qb = TSeikyu::select('t_seikyu.*')->filter($request);
        $qb->joinMNinusi()->addSelect('m_ninusi.ninusi_ryaku_nm');
        $qb->selectRaw("CONCAT_WS('::', t_seikyu.ninusi_cd, t_seikyu.seikyu_sime_dt) AS pk");
        $this->joinTUriageMikakutei($joinType, $qb,
            [
                'seikyu_sime_dt',
                $this->getRawSelect('mikakutei_su'),
                $this->getRawSelect('sum_mikakutei_kin', 'mikakutei_kin')
            ],
            ['seikyu_sime_dt'] //group-by
        );
        $qb->addSelect([
            'mikakutei_su', 'mikakutei_kin',
            $this->getRawSelect('nyukin_kin')
        ]);

        return $qb;
    }

    // 11.請求一覧
    public function getList($request)
    {
        $qb = $this->getListBuilder($request);

        if ($request->filled('ninusi_nm')) {
            $qb->where('m_ninusi.ninusi_ryaku_nm', 'ILIKE', makeEscapeStr($request->ninusi_nm) . '%');
        }

        return ['total' => $qb->count(), 'rows' => $qb];
    }

    public function getOptionOpts() {
        return [
            //'S' => ['text' => '内訳を出力する（S）'],
            self::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力あり（M）'],
        ];
    }

    public function joinTUriageMikakutei($type, &$qb, $tUriageAddSelect, $groupBy = [])
    {
        $tUriage = TUriage::query()
            ->join('m_ninusi',  't_uriage.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->selectRaw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) AS seikyu_cd')
            ->where('unchin_mikakutei_kbn', '1');

        $tUriage->addSelect($tUriageAddSelect);

        $groups = [DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)')];
        if (!empty($groupBy)) {
            $groups = array_merge($groups, $groupBy);
            $tUriage->groupBy(...$groups);
        }

        $qb->joinSub($tUriage, 't_uriage' , function ($j) {
            $j->on("t_seikyu.ninusi_cd", '=', "t_uriage.seikyu_cd");
            $j->on("t_seikyu.seikyu_sime_dt", '=', "t_uriage.seikyu_sime_dt");
        }, null, null, $type);
    }

    public function qbExport(Request $request)
    {
        $qb = TSeikyu::select([
            't_seikyu.seikyu_sime_dt',
            't_seikyu.ninusi_cd',
            'zenkai_seikyu_kin',
            $this->getRawSelect('nyukin_kin_5', 'nyukin_kin'),//入金額
            DB::raw("(COALESCE(sousai_kin, 0) + COALESCE(nebiki_kin, 0)) AS sousai_nebiki"),//相殺・値引 'sousai_kin+nebiki-kin',
            'kjrikosi_kin',//繰越額
            DB::raw("(COALESCE(kazei_unchin_kin, 0) + COALESCE(kazei_tyukei_kin, 0) "
                ."+ COALESCE(kazei_tukouryou_kin, 0) + COALESCE(kazei_niyakuryo_kin, 0)) AS kazei_kin"),//課税運賃
            'zei_kin',//消費税
            DB::raw("(COALESCE(hikazei_unchin_kin, 0) + COALESCE(hikazei_tyukei_kin, 0) "
                ."+ COALESCE(hikazei_tukouryo_kin, 0) + COALESCE(hikazei_niyakuryo_kin, 0)) AS hikazei_kin"),//非課税額
            'konkai_torihiki_kin',//今回取引額
            'konkai_torihiki_kin AS seikyu_kin',//請求額

            'sousai_kin',
            'nebiki_kin',
            'genkin_kin',
            'furikomi_kin',
            'furikomi_tesuryo_kin',
            'tegata_kin',
            'sonota_nyu_kin',

            'kazei_unchin_kin',
            'kazei_tyukei_kin',
            'kazei_tukouryou_kin',
            'kazei_niyakuryo_kin',

            'hikazei_unchin_kin',
            'hikazei_tyukei_kin',
            'hikazei_tukouryo_kin',
            'hikazei_niyakuryo_kin',
        ]);

        $qb->joinMNinusi()->addSelect([
            'm_ninusi.ninusi1_nm',
            'm_ninusi.kaisyu1_dd', 'm_ninusi.kaisyu2_dd',
            'zei_keisan_kbn']);

        $this->joinTUriageMikakutei('left', $qb,
            [
                'seikyu_sime_dt',
                $this->getRawSelect('mikakutei_su'),
                $this->getRawSelect('sum_mikakutei_kin', 'mikakutei_kin')
            ],
            ['seikyu_sime_dt'] //group-by
        );

        $qb->addSelect([
            DB::raw('COALESCE(mikakutei_su, 0) AS mikakutei_su'),
            DB::raw('COALESCE(mikakutei_kin, 0) AS mikakutei_kin'),
        ]);

        $qb->orderBy('t_seikyu.seikyu_sime_dt');
        $qb->orderBy('t_seikyu.ninusi_cd');
        $qb->orderBy('t_seikyu.seikyu_no');

        return $qb;
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);

        $items = json_decode($cloneReq->get('selected_items', "[]"));
        $qb->where(function ($q) use ($items) {
            foreach ($items as $item) {
                $q->orWhere(function($qItem) use ($item) {
                    $keys = explode('::', $item);
                    $qItem->where('t_seikyu.ninusi_cd', $keys[0]);
                    $qItem->where('t_seikyu.seikyu_sime_dt', $keys[1]);
                });
            }
        });

        return $qb;
    }

    public function applySelectedItemsToBuilder(Request $request, &$qb, $key = 'selected_items', $jsonEncode = true)
    {
        $items = $jsonEncode
            ? json_decode(data_get($request->all(), $key, "[]"))
            : data_get($request->all(), $key, []);
        $qb->where(function ($q) use ($items) {
            foreach ($items as $item) {
                $q->orWhere(function($qItem) use ($item) {
                    $keys = explode('::', $item);
                    $qItem->where('t_seikyu.ninusi_cd', $keys[0]);
                    $qItem->where('t_seikyu.seikyu_sime_dt', $keys[1]);
                });
            }
        });
    }

    public static function getKaisyuYoteiDt($seikyuSimeDt, $kaisyu1Dd, $kaisyu2Dd = null)
    {
        $kaisyuDt = Carbon::parse($seikyuSimeDt);
        $kaisyuDt = $kaisyuDt->addMonths($kaisyu1Dd);
        if (!empty($kaisyu2Dd)) {
            $endOfMonth = (clone $kaisyuDt)->endOfMonth();
            $kaisyuDt = $endOfMonth->format('Ymd') >= $kaisyuDt->format('Ym') . str_pad($kaisyu2Dd, 2, '0', STR_PAD_LEFT)
                ? $kaisyuDt->setDay($kaisyu2Dd)
                : $endOfMonth;
        }
        return $kaisyuDt;
    }
}
