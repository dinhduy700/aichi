<?php

namespace App\Http\Repositories\Seikyu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\TUriage;

class SeikyuRepository
{
    const EXP_PRINT_OTHER_DISPLAY_NYUKIN        = '1';
    const EXP_PRINT_OTHER_CSV_HEADER            = '2';
    
    public function getListWithTotalCount($request)
    {
        $qb = DB::table('t_seikyu')
                ->select([
                    't_seikyu.zenkai_seikyu_kin',
                    't_seikyu.ninusi_cd',
                    'm_ninusi.ninusi_ryaku_nm',
                    't_seikyu.konkai_torihiki_kin as seikyu_kin',
                    't_seikyu.konkai_torihiki_kin',
                    DB::raw("CASE WHEN seikyu_hako_flg = '1' THEN '済' ELSE NULL END as seikyu_hako_flg"),
                    DB::raw("CASE WHEN seikyu_kakutei_flg = '1' THEN '済' ELSE NULL END as seikyu_kakutei_flg"),

                    DB::raw('(COALESCE(t_seikyu.genkin_kin, 0) + COALESCE(t_seikyu.furikomi_kin, 0)
                            + COALESCE(t_seikyu.furikomi_tesuryo_kin, 0) + COALESCE(t_seikyu.tegata_kin, 0) 
                            + COALESCE(t_seikyu.sonota_nyu_kin, 0) + COALESCE(t_seikyu.sousai_kin, 0)
                            + COALESCE(t_seikyu.nebiki_kin, 0)
                            ) as nyukin_kin'),

                ])
                ->join('m_ninusi', 't_seikyu.ninusi_cd', '=', 'm_ninusi.ninusi_cd');


        
        $qb->where('t_seikyu.seikyu_sime_dt', $request->seikyu_sime_dt);
                    
        $qb->orderBy('m_ninusi.ninusi_cd');

        $total = $qb->count();
        
        return [
            'total' => $total,
            'rows' => $qb,
        ];
    }

    public function getExportMidasisiteiOpts()
    {
        return [
            '1' => [
                'text' => '０：通常',
            ],
            '2' => [
                'text' => '１：控',
            ],
            '3' => [
                'text' => '２：両方',
            ],
        ];
    }

    public function getExportSeikyusskOpts()
    {
        return [
            '1' => [
                'text' => '０：通常',
            ],
            '2' => [
                'text' => '１：今回売上分のみ',
            ],
        ];
    }

    public function getExportPrintOtherOpts() 
    {
        return [
            '1' => [
                'text' => '入金明細を印字する（N）'
            ],
            '2' => [
                'text' => 'CSV見出し出力あり（M)',
            ],
        ];
    }

    public function qbExport($request, $route = null)
    {
        $tUriage    = new TUriage();
        $table      = $tUriage->getTable();
        $qb         = $tUriage->filter($request);// $qb = new Builder();

        $qb->joinTSeikyu()
            ->addSelect([
                "t_seikyu.zenkai_seikyu_kin", //前回御請求額
                "t_seikyu.sousai_kin", // 相殺・値引き
                "t_seikyu.kjrikosi_kin", // 繰越額
                "t_seikyu.kazei_unchin_kin", // 課税運賃
                "t_seikyu.zei_kin", // 消費税
                "t_seikyu.konkai_torihiki_kin", // 今回取引額
                "t_seikyu.seikyu_sime_dt",
                "t_seikyu.ninusi_cd",
                "t_seikyu.ninusi_cd as seikyu_gp_1",
                "t_seikyu.ninusi_cd as seikyu_saki_kodo",
                "t_seikyu.genkin_kin",
                "t_seikyu.furikomi_kin",
                "t_seikyu.furikomi_tesuryo_kin",
                "t_seikyu.tegata_kin",
                "t_seikyu.sonota_nyu_kin",
                "t_seikyu.nebiki_kin",
                "t_seikyu.kazei_tyukei_kin",
                "t_seikyu.kazei_tukouryou_kin",
                "t_seikyu.kazei_niyakuryo_kin",
                "t_seikyu.hikazei_unchin_kin",
                "t_seikyu.hikazei_tyukei_kin",
                "t_seikyu.hikazei_tukouryo_kin",
                "t_seikyu.hikazei_niyakuryo_kin",
            ]);
       
        $qb->leftJoin('m_ninusi', "t_seikyu.ninusi_cd", '=', "m_ninusi.ninusi_cd")
            ->addSelect(["m_ninusi.yubin_no", 
                         "m_ninusi.simebi1", 
                         "m_ninusi.ninusi1_nm", 
                         "m_ninusi.ninusi2_nm",
                         "m_ninusi.jyusyo1_nm",
                         "m_ninusi.jyusyo2_nm",
                         "m_ninusi.ninusi_ryaku_nm",
                         "m_ninusi.zei_keisan_kbn",
                    ]);
        $qb->joinMSyaryo()->addSelect(['m_syaryo.syaryo_cd']);

        switch ($route) {
            case 'seikyu.seikyu_sho.exp.xls':
            case 'seikyu.seikyu_sho.exp.pdf':
                $qb->addSelect([
                    "{$table}.unso_dt", // 運送日
                    "{$table}.syaban", // 運送日
                    "{$table}.su", // 数量
                    "{$table}.tukoryo_kin", // 通行料等
                    DB::raw("CONCAT(m_meisyo_syasyu.meisyo_cd, m_meisyo_syasyu.meisyo_nm) as syasyu"),
                    DB::raw("(COALESCE({$table}.unchin_kin,0) 
                            + COALESCE({$table}.tyukei_kin,0)
                            + COALESCE({$table}.syuka_kin,0)
                            + COALESCE({$table}.tesuryo_kin,0)
                            + COALESCE({$table}.tukoryo_kin,0)
                            + COALESCE({$table}.nieki_kin,0)
                            ) as unchin_gokei"),
        
                    DB::raw("(COALESCE({$table}.unchin_kin,0) 
                            + COALESCE({$table}.tyukei_kin,0)
                            + COALESCE({$table}.syuka_kin,0)
                            + COALESCE({$table}.tesuryo_kin,0)
                            + COALESCE({$table}.nieki_kin,0)
                            ) as kihon_unchin"),
                ]);
                $qb->joinMHachaku()->addSelect("m_hachaku.hachaku_nm");
                $qb->JoinHatuti()->addSelect("m_hatuti.hachaku_nm AS hatuti_nm");
                $qb->joinMHinmei()->addSelect("hinmei_nm");
                $qb->joinMMeisyoTani()->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");
                $qb->leftJoin("m_meisyo AS m_meisyo_syasyu", function ($j) {
                    $j->on("m_syaryo.syasyu_cd", '=', "m_meisyo_syasyu.meisyo_cd");
                    $j->where("m_meisyo_syasyu.meisyo_kbn", '=', configParam('MEISYO_KBN_SYASYU'));
                });
                break;
        }
        
        return $qb;
    }

    public function applyRequestToBuilder(Request $request) 
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
    
        $qb = $this->qbExport($cloneReq, $routeNm);

        if (isset($request->list_ninusi_cd)) {
            $qb->whereIn('t_seikyu.ninusi_cd', explode(',', $request->list_ninusi_cd));
        }

        if (!empty($request->seikyu_sime_dt)) {
            $qb->where('t_seikyu.seikyu_sime_dt', $request->seikyu_sime_dt);
        }

        switch ($routeNm) {
            case 'seikyu.seikyu_sho.exp.xls':
            case 'seikyu.seikyu_sho.exp.pdf':
                $qb->orderBy('t_uriage.unso_dt');
                break;
        }
        
        return $qb;
    }

    public function csvHeaderRow($request)
    {
        $qb = DB::table('t_seikyu')
            ->select([
                't_seikyu.*',
                'm_ninusi.yubin_no',
                'm_ninusi.jyusyo1_nm',
                'm_ninusi.jyusyo2_nm',
                'm_ninusi.ninusi1_nm',
                'm_ninusi.ninusi2_nm',
                'm_ninusi.zei_keisan_kbn',
                't_uriage.seikyu_sime_dt as seikyu_shimebi', // 請求締日
            ])
            ->leftJoin('m_ninusi', 't_seikyu.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->leftJoin('t_uriage', function ($j) {
                $j->on("t_seikyu.seikyu_sime_dt", '=', "t_uriage.seikyu_sime_dt");
                $j->on("t_seikyu.seikyu_no", '=', "t_uriage.seikyu_no");
            });
            
        if ($listNinusiCd = data_get($request, 'list_ninusi_cd')) {
            $qb->whereIn('t_seikyu.ninusi_cd', explode(',', $listNinusiCd));
        }

        if ($seikyuSimeDt = data_get($request, 'seikyu_sime_dt')) {
            $qb->where('t_seikyu.seikyu_sime_dt', $seikyuSimeDt);
        }

        $qb->orderBy('t_seikyu.seikyu_sime_dt');
        $qb->orderBy('t_seikyu.ninusi_cd');

        return $qb;
    }

    public function csvDetailTUriage($request)
    {
        $tUriage    = new TUriage();
        $table      = $tUriage->getTable();
        $qb         = new TUriage();

        $qb = $qb->addSelect([
            "{$table}.seikyu_kin_tax",
            "{$table}.unchin_kin",
            "{$table}.menzei_kbn",
            "{$table}.tyukei_kin",
            "{$table}.tukoryo_kin",
            "{$table}.nieki_kin",
            "{$table}.syuka_kin",
            "{$table}.tesuryo_kin",
            "{$table}.seikyu_sime_dt as seikyu_shimebi", // 請求締日
            "{$table}.unso_dt",
            "{$table}.syaban",
            "{$table}.hatuti_cd",
            "{$table}.hachaku_cd",
            "{$table}.hinmei_cd",
            "{$table}.su",
            "{$table}.tani_cd",
            "{$table}.tanka_kbn",
            "{$table}.seikyu_tanka",
            "{$table}.unchin_mikakutei_kbn",
            "{$table}.biko_cd",
            "{$table}.biko",
            "{$table}.jyomuin_cd",
            "{$table}.yousya_cd",
            "{$table}.syuka_dt",
            "{$table}.syuka_tm",
            "{$table}.haitatu_dt",
            "{$table}.jikoku",
            "{$table}.seikyu_keijyo_dt",
            "{$table}.genkin_cd",
            "{$table}.syubetu_cd",
            "{$table}.gyosya_cd",
            "{$table}.uriage_den_no",
            "{$table}.ninusi_cd as ninushi_kodo",
            "{$table}.unso_dt as dt",
            DB::raw('1 as deta_kbn'),
            DB::raw('1 as meisai_kbn'),
            DB::raw('0 as nyukin_no'),
            DB::raw('0 as gyo_nyukin'),
        ]);

        $qb->joinTSeikyu()
            ->addSelect([
                "t_seikyu.zenkai_seikyu_kin", //前回御請求額
                "t_seikyu.sousai_kin", // 相殺・値引き
                "t_seikyu.kjrikosi_kin", // 繰越額
                "t_seikyu.kazei_unchin_kin", // 課税運賃
                "t_seikyu.zei_kin", // 消費税
                "t_seikyu.konkai_torihiki_kin", // 今回取引額
                "t_seikyu.seikyu_sime_dt",
                "t_seikyu.ninusi_cd",
                "t_seikyu.ninusi_cd as seikyu_gp_1",
                "t_seikyu.ninusi_cd as seikyu_saki_kodo",
            ]);
       
        $qb->leftJoin('m_ninusi', "t_seikyu.ninusi_cd", '=', "m_ninusi.ninusi_cd")
            ->addSelect(["m_ninusi.yubin_no", 
                         "m_ninusi.simebi1", 
                         "m_ninusi.ninusi1_nm", 
                         "m_ninusi.ninusi2_nm",
                         "m_ninusi.jyusyo1_nm",
                         "m_ninusi.jyusyo2_nm",
                         "m_ninusi.ninusi_ryaku_nm",
                         "m_ninusi.zei_keisan_kbn",
                    ]);
        $qb->joinMSyaryo()->addSelect(['m_syaryo.syaryo_cd']);

        $qb->joinMHachaku()->addSelect("m_hachaku.hachaku_nm");
        $qb->JoinHatuti()->addSelect("m_hatuti.hachaku_nm AS hatuti_nm");
        $qb->joinMHinmei('left', 'm_hinmei', true)->addSelect(["hinmei_nm", "m_hinmei.hinmoku_cd", "hinmoku_nm"]);
        $qb->joinMJyomuin()->addSelect(["jyomuin_nm"]);
        $qb->joinMYousya()->addSelect(["yousya1_nm"]);
        $qb->joinMMeisyoTani()->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");
        $qb->joinMMeisyoGyosya()->addSelect("m_meisyo_gyosya.meisyo_nm AS gyosya_nm");
        $qb->joinMMeisyoGenkin()->addSelect("m_meisyo_genkin.meisyo_nm AS genkin_nm");
        $qb->joinMMeisyoSyubetu()->addSelect("m_meisyo_syubetu.meisyo_nm AS syubetu_nm");

        $qb->leftJoin("m_meisyo AS m_meisyo_rikuun", function ($j) {
            $j->on("m_meisyo_rikuun.meisyo_cd", '=', "m_syaryo.rikuun_cd");
            $j->where("m_meisyo_rikuun.meisyo_kbn", '=', configParam('MEISYO_KBN_RIKUUN'));
        })->addSelect([
            DB::raw("CONCAT(m_meisyo_rikuun.meisyo_nm, ' ', car_number_syubetu, car_number_kana, car_number) as card_nm"),
        ]);

        if ($listNinusiCd = data_get($request, 'list_ninusi_cd')) {
            $qb->whereIn('t_seikyu.ninusi_cd', explode(',', $listNinusiCd));
        }

        if ($seikyuSimeDt = data_get($request, 'seikyu_sime_dt')) {
            $qb->where('t_seikyu.seikyu_sime_dt', $seikyuSimeDt);
        }

        $qb->orderBy('t_uriage.unso_dt');
       
        return $qb;
    }

    public function csvDetailTNyukin($request)
    {
        $qb = DB::table('m_ninusi')
            ->select([
                't_nyukin.seikyu_sime_dt as seikyu_shimebi',
                't_nyukin.ninusi_cd as ninushi_kodo',
                't_nyukin.nyukin_dt as dt',
                't_nyukin.nyukin_no',
                'm_ninusi.urikake_saki_cd as seikyu_gp_1',
                'm_ninusi.urikake_saki_cd as seikyu_saki_kodo',
                DB::raw('1 as deta_kbn'),
                DB::raw('2 as meisai_kbn'),
                DB::raw('2 as gyo_nyukin'),
            ])
            ->join('t_nyukin', 'm_ninusi.ninusi_cd', '=', 't_nyukin.ninusi_cd');

        $qb->addSelect([
            't_nyukin.*',
            'm_ninusi.urikake_saki_cd',
            DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) as seikyu_cd'),
        ]);

        if ($listNinusiCd = data_get($request, 'list_ninusi_cd')) {
            $qb->whereIn('t_nyukin.ninusi_cd', explode(',', $listNinusiCd));
        }

        if ($seikyuSimeDt = data_get($request, 'seikyu_sime_dt')) {
            $qb->where('t_nyukin.seikyu_sime_dt', $seikyuSimeDt);
        }

        $qb->orderBy('t_nyukin.nyukin_dt');

        return $qb;
    }

    public function csvTotalRow($request)
    {
        $tUriage    = new TUriage();
        $table      = $tUriage->getTable();
        $qb         = new TUriage();
        $qb = $qb->addSelect([
            "{$table}.seikyu_kin_tax",
            "{$table}.unchin_kin",
            "{$table}.menzei_kbn",
            "{$table}.tyukei_kin",
            "{$table}.tukoryo_kin",
            "{$table}.nieki_kin",
            "{$table}.syuka_kin",
            "{$table}.tesuryo_kin",
            "{$table}.seikyu_sime_dt as seikyu_shimebi", // 請求締日
        ]);
        $qb->joinTSeikyu()
            ->addSelect([
                "t_seikyu.ninusi_cd",
                "t_seikyu.ninusi_cd as seikyu_gp_1",
                "t_seikyu.ninusi_cd as seikyu_saki_kodo",
            ]);

        if ($listNinusiCd = data_get($request, 'list_ninusi_cd')) {
            $qb->whereIn('t_seikyu.ninusi_cd', explode(',', $listNinusiCd));
        }

        if ($seikyuSimeDt = data_get($request, 'seikyu_sime_dt')) {
            $qb->where('t_uriage.seikyu_sime_dt', $seikyuSimeDt);
        }

        return $qb;
    }


    public function getMaxSeikyuSimeDt()
    {
        $result = DB::table('t_seikyu')
            ->select([
                DB::raw('MAX(seikyu_sime_dt) as max_date')
            ])
            ->value('max_date');
        
        return $result;
    }

    public function updateTSeikyu($listNinusiCd, $seikyuSimeDt)
    {
        DB::table('t_seikyu')
            ->whereIn('ninusi_cd', explode(',', $listNinusiCd))
            ->where('seikyu_sime_dt', $seikyuSimeDt)
            ->update([
                'seikyu_hako_flg' => 1
            ]);
    }

    public function updateTUriage($listNinusiCd, $seikyuSimeDt)
    {
        DB::table('t_uriage')
            ->whereIn('ninusi_cd', explode(',', $listNinusiCd))
            ->where('seikyu_sime_dt', $seikyuSimeDt)
            ->update([
                'unchin_mikakutei_kbn' => 9
            ]);
    }
}