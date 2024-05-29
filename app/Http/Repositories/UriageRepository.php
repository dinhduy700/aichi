<?php
namespace App\Http\Repositories;

use App\Models\TUriage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

class UriageRepository
{
    //
    public function getExportOrderByOpts()
    {
        return [
            '1' => [
                'text' => '部門コード、伝票NO順',
                'orderBy' => [
                    ['bumon_cd', 'asc'],
                    ['uriage_den_no', 'asc'],
                ]
            ],
            '2' => [
                'text' => '部門コード、商品種別コード、品名コード順',
                'orderBy' => [
                    ['bumon_cd', 'asc'],
                    ['syubetu_cd', 'asc'],
                    ['hinmei_cd', 'asc'],
                    ['uriage_den_no', 'asc'],
                ]
            ],
            '3' => [
                'text' => '部門コード、業者コード、商品種別コード、品名コード順',
                'orderBy' => [
                    ['bumon_cd', 'asc'],
                    ['gyosya_cd', 'asc'],
                    ['syubetu_cd', 'asc'],
                    ['hinmei_cd', 'asc'],
                    ['uriage_den_no', 'asc'],
                ]
            ],
        ];
    }

    public function qbExport($request, $applyOrderBy = true, $routeNm = null)
    {
        $tUriage = new TUriage();
        $table = $tUriage->getTable();
        $qb = $tUriage->newQuery()->filter($request);// $qb = new Builder();
        $qb->select("{$table}.*");
        // 部門マスタ
        $qb->joinMBumon()->addSelect("m_bumon.bumon_nm");
        // 荷主マスタ
        $qb->joinMNinusi()->addSelect("m_ninusi.ninusi_ryaku_nm");
        // 発地着地マスタ
        $qb->joinMHachaku()->addSelect("m_hachaku.hachaku_nm");
        $qb->JoinHatuti()->addSelect("m_hatuti.hachaku_nm AS hatuti_nm");
        // 庸車先マスタ
        $qb->joinMYousya()->addSelect("yousya1_nm");
        // 品名マスタ, 品目マスタ
        $qb->joinMHinmei('left', 'm_hinmei', true)->addSelect(["hinmei_nm", "m_hinmei.hinmoku_cd", "hinmoku_nm"]);
        // 乗務員マスタ
        $qb->joinMJyomuin()->addSelect("m_jyomuin.jyomuin_nm");
        // 名称マスタ
        $qb->joinMMeisyoTani()->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");
        $qb->joinMMeisyoGyosya()->addSelect("m_meisyo_gyosya.meisyo_nm AS gyosya_nm");

        if ($applyOrderBy) {
            foreach ($this->getExportOrderByOpts()[$request->orderBy ?? '1']['orderBy'] as $order) {
                $qb->orderBy(...$order);
            };
        }

        switch ($routeNm) {
            case 'uriage.exp.csv':
                $qb->addSelect(['m_ninusi.ninusi1_nm', 'm_ninusi.ninusi2_nm', 'm_hinmei.hinmei2_cd']);
                $qb->leftJoin('m_jyomuin AS m_jyomuin_tanto', "{$table}.add_tanto_cd", '=', 'm_jyomuin_tanto.jyomuin_cd')
                    ->addSelect('m_jyomuin_tanto.jyomuin_nm AS add_tanto_jyomuin_nm');
                $qb->joinMMeisyoGenkin()->addSelect("m_meisyo_genkin.meisyo_nm AS genkin_nm");
                $qb->joinMMeisyoSyubetu()->addSelect("m_meisyo_syubetu.meisyo_nm AS syubetu_nm");
                $qb->addSelect([
                    'm_ninusi.zei_keisan_kbn AS m_ninusi_zei_keisan_kbn',
                    'm_yousya.zei_keisan_kbn AS m_yousya_zei_keisan_kbn',
                ]);

                $qb->leftJoin('m_ninusi AS m_ninusi_urikake_saki', 'm_ninusi.urikake_saki_cd', '=', 'm_ninusi_urikake_saki.ninusi_cd')
                    ->addSelect([
                        'm_ninusi.urikake_saki_cd',
                        'm_ninusi_urikake_saki.ninusi_ryaku_nm AS urikake_saki_nm',
                    ]);

                $qb->leftJoin('m_ninusi AS m_ninusi_seikyu', 'm_ninusi.seikyu_cd', '=', 'm_ninusi_seikyu.ninusi_cd')
                    ->addSelect([
                        'm_ninusi.seikyu_cd',
                        'm_ninusi_seikyu.ninusi_ryaku_nm AS seikyu_nm',
                    ]);

                $qb->joinMSyaryo()->addSelect(['m_syaryo.jiyo_kbn'])
                    //請求車種 m_syaryo.syasyu_cd
                    ->leftJoin('m_meisyo AS m_meisyo_syasyu', function ($j) {
                        $j->on('m_syaryo.syasyu_cd', '=', 'm_meisyo_syasyu.meisyo_cd');
                        $j->where('m_meisyo_syasyu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYASYU'));
                    })->addSelect(['m_syaryo.syasyu_cd', 'm_meisyo_syasyu.meisyo_nm AS syasyu_nm'])
                    //部門(車両) m_syaryo.bumon_cd
                    ->leftJoin('m_bumon AS m_bumon_syaryo', 'm_syaryo.bumon_cd', '=', 'm_bumon_syaryo.bumon_cd')
                    ->addSelect(['m_syaryo.bumon_cd AS syaryo_bumon_cd', 'm_bumon_syaryo.bumon_nm AS syaryo_bumon_nm']);

                $qb->leftJoin('m_bumon AS m_bumon_jyomuin', 'm_jyomuin.bumon_cd', '=', 'm_bumon_jyomuin.bumon_cd')
                    ->addSelect(['m_jyomuin.bumon_cd AS jyomuin_bumon_cd', 'm_bumon_jyomuin.bumon_nm AS jyomuin_bumon_nm']);

                break;
        }

        return $qb;
    }

    //
    public function getNouhinsyoSyuturyokuOpts()
    {
        return [
            '1' => [
                'text' => '未発行分',
                'where' => function (&$qb, $table = 't_uriage') {
                    $qb->where(function ($q) use ($table) {
                        $q->whereNull("{$table}.okurijyo_no")
                            ->orWhere("{$table}.okurijyo_no", '=', '');
                    });
                }
            ],
            '2' => [
                'text' => 'すべて',
                'where' => function (&$qb, $table = 't_uriage') {
                    // TODO: nothing
                }
            ],
        ];
    }

    public function getNouhinsyoSyuhaiOpts()
    {
        return [
            'S' => [
                'text' => '集区分（S）',
            ],
            'K' => [
                'text' => '中継分（K）',
            ],
            'H' => [
                'text' => '配達分（H）',
            ],
            'B' => [
                'text' => '直送分割分（B）',
            ],
            'C' => [
                'text' => '直送分（C）',
            ],
            'A' => [
                'text' => '請求分（A）',
            ],
        ];
    }

    public function getNouhinsyoOtherOpts()
    {
        return [
//            'Y' => [
//                'text' => '傭車先印字有り（Y）',
//            ],
            'J' => [
                'text' => '配車済のみ印字（J）',
                'where' => function (&$qb, $table = 't_uriage') {
                    $qb->where(function ($q) use ($table) {
                        $q->whereNotNull("{$table}.syaban")
                            ->where("{$table}.syaban", '!=', '');
                    });
                },
                'checked' => true
            ],
        ];
    }

    public function getNouhinsyoQb(Request $request) {
        $cloneReq = new Request($request->exp ?? []);
        // $routeNm = $request->route()->getName();

        $qb = $this->qbExport($cloneReq, false);

        // 荷受人 - 着地CD
        $qb->addSelect([
            'm_hatuti.jyusyo1_nm AS hatuti_jyusyo1_nm',
            'm_hatuti.jyusyo2_nm AS hatuti_jyusyo2_nm',
            'm_hatuti.atena AS hatuti_atena',
            'm_hatuti.tel AS hatuti_tel',
        ]);

        // 荷送人 - 発地CD
        $qb->addSelect([
            'm_hachaku.jyusyo1_nm AS hachaku_jyusyo1_nm',
            'm_hachaku.jyusyo2_nm AS hachaku_jyusyo2_nm',
            'm_hachaku.atena AS hachaku_atena',
            'm_hachaku.tel AS hachaku_tel',
        ]);

        // 御依頼人
        $qb->addSelect([
            'm_ninusi.ninusi1_nm AS ninusi_ninusi1_nm',
            'm_ninusi.ninusi2_nm AS ninusi_ninusi2_nm',
        ]);

        // 出力区分
        if ($cloneReq->filled('syuturyoku')) {
        $opts = $this->getNouhinsyoSyuturyokuOpts();
        $func = $opts[$cloneReq->syuturyoku]['where'];
        $qb->where($func($qb));
        }

        //オプション_配車済のみ印字
        $opts = $this->getNouhinsyoOtherOpts();
        foreach ($cloneReq->get('option', []) as $opt) {
            $func = $opts[$opt]['where'];
            $qb->where($func($qb));
        }

        $qb->orderBy('okurijyo_no');
        $qb->orderBy('haitatu_dt');
        $qb->orderBy('hatuti_cd');
        $qb->orderBy('hachaku_cd');
        $qb->orderBy('uriage_den_no');

        return $qb;
    }

    public function handleNouhinsyoOkurijyoNo($qb, $request)
    {
        $uriageDenNos = $qb->pluck('uriage_den_no');
        TUriage::whereIn('uriage_den_no', $uriageDenNos)
            ->where(function ($q) {
                $q->whereNull("okurijyo_no")->orWhere("okurijyo_no", '=', '');
            })->update([
                'okurijyo_no' => \Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(max(okurijyo_no::INTEGER), 0) + 1 FROM t_uriage)')
            ]);
        $request->replace($request->except('exp.syuturyoku'));
        $qb = $this->getNouhinsyoQb($request);
        $qb->whereIn('uriage_den_no', $uriageDenNos);
        return $qb;
    }
    //

    public function getListWithTotalCount($request, $keyInitSearch)
    {
        $tableAndColumn = $this->__getTableAndColumn();
        $tUriage = new TUriage();
        $table = $tUriage->getTable();
        $qb = $tUriage->filterList($request, $tableAndColumn);
        // $qb->select("{$table}.*", DB::raw("(CASE WHEN {$table}.genkin_cd = '1' THEN '現金' ELSE '' END) as genkin_nm"));
        $qb->select("{$table}.*");
        $qb->joinMBumon("left")->addSelect("bumon_nm");
        $qb->joinMNinusi("left")->addSelect("m_ninusi.ninusi_ryaku_nm as ninusi_nm");
        $qb->joinMHachaku("left")->addSelect("m_hachaku.hachaku_nm");
        $qb->JoinHatuti("left")->addSelect(["m_hatuti.hachaku_nm AS hatuti_nm", 'm_hatuti.hachaku_cd as hatuti_cd']);
        $qb->joinMYousya("left")->addSelect("yousya1_nm as yousya_nm");
        $qb->joinMHinmei('left', 'm_hinmei', true)->addSelect(["hinmei_nm", "m_hinmei.hinmoku_cd", "hinmoku_nm"]);
        $qb->joinMMeisyoTani("left")->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");
        $qb->joinMMeisyoGyosya("left")->addSelect("m_meisyo_gyosya.meisyo_nm AS gyosya_nm");
        $qb->joinMJyomuin("left")->addSelect("jyomuin_nm");
        $qb->leftjoin('m_meisyo as m_meisyo_syubetu', function($query) use ($table) {
            $query->on('m_meisyo_syubetu.meisyo_cd', '=', "${table}.syubetu_cd");
            $query->where('m_meisyo_syubetu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYUBETU'));
        })->addSelect("m_meisyo_syubetu.meisyo_nm as syubetu_nm");

        $qb->leftjoin('m_meisyo as m_meisyo_genkin', function($query) use ($table) {
            $query->on('m_meisyo_genkin.meisyo_cd', '=', "${table}.genkin_cd");
            $query->where('m_meisyo_genkin.meisyo_kbn', '=', configParam('MEISYO_KBN_GENKIN'));
        })->addSelect("m_meisyo_genkin.meisyo_nm as genkin_nm");

        $qb->leftjoin('m_meisyo as m_meisyo_unchin_kakutei', function($query) use ($table) {
            $query->on('m_meisyo_unchin_kakutei.meisyo_cd', '=', "${table}.unchin_mikakutei_kbn");
            $query->where('m_meisyo_unchin_kakutei.meisyo_kbn', '=', configParam('MEISYO_KBN_UNCHINKAKUTEI'));
        })->addSelect("m_meisyo_unchin_kakutei.meisyo_nm as unchin_mikakutei_nm");

        if($request->route()->getName() == 'jyutyu.order_entry.data_list' || $request->route()->getName() == 'order.order_entry.dispatch_list') {
            $qb->joinMMeisyoJyutyu("left")->addSelect('m_meisyo_jyutyu.meisyo_nm as jyutyu_nm');
        }
        if($request->route()->getName() == 'jyutyu.order_entry.data_list' || $request->route()->getName() == 'order.order_entry.dispatch_list') {
            $qb->joinMMeisyoTanka("left")->addSelect('m_meisyo_tanka.meisyo_nm as tanka_nm');
        }
        if($request->filled('hed_add_tanto_cd')) {
            $qb->where('t_uriage.add_tanto_cd', 'ILIKE', makeEscapeStr($request->hed_add_tanto_cd) . '%');
        }

        if($request->filled('hed_jyutyu_nm')) {
            $qb->where('m_meisyo_jyutyu.meisyo_nm', 'ILIKE', makeEscapeStr($request->hed_jyutyu_nm) . '%');
        }

        if($request->filled('hed_unso_dt_from')) {
            $qb->where("{$table}.unso_dt", '>=', $request->hed_unso_dt_from);
        }
        if($request->filled('hed_unso_dt_to')) {
            $qb->where("{$table}.unso_dt", '<=', $request->hed_unso_dt_to);
        }

        if($request->filled('hed_syuka_dt_from')) {
            $qb->where("{$table}.syuka_dt", '>=', $request->hed_syuka_dt_from);
        }
        if($request->filled('hed_syuka_dt_to')) {
            $qb->where("{$table}.syuka_dt", '<=', $request->hed_syuka_dt_to);
        }

        if($request->filled('hed_haitatu_dt_from')) {
            $qb->where("{$table}.haitatu_dt", '>=', $request->hed_haitatu_dt_from);
        }
        if($request->filled('hed_haitatu_dt_to')) {
            $qb->where("{$table}.haitatu_dt", '<=', $request->hed_haitatu_dt_to);
        }

        if($request->filled('hed_bumon_cd')) {
            $qb->where(\DB::raw("{$table}.bumon_cd"), 'ILIKE', strtolower(makeEscapeStr($request->hed_bumon_cd)) . '%');
        }

        if($request->filled('hed_bumon_nm')) {
            $qb->where(\DB::raw("m_bumon.bumon_nm"), 'ILIKE', strtolower(makeEscapeStr($request->hed_bumon_nm)) . '%');
        }

        if($request->filled('hed_jyomuin_cd')) {
            $qb->where(\DB::raw("{$table}.jyomuin_cd"), 'ILIKE', strtolower(makeEscapeStr($request->hed_jyomuin_cd)) . '%');
        }

        if($request->filled('hed_jyomuin_nm')) {
            $qb->where(\DB::raw("m_jyomuin.jyomuin_nm"), 'ILIKE', strtolower(makeEscapeStr($request->hed_jyomuin_nm)) . '%');
        }
        if($request->filled('radio_hed_syaban')) {
            if($request->radio_hed_syaban == 'mihaisya') {
                $qb->whereNull("{$table}.syaban");
            } else if($request->radio_hed_syaban == 'haisyazumi') {
                $qb->whereNotNull("{$table}.syaban");
            }
        }

        if(!empty($request->genkin_cd)) {
            if(is_array($request->genkin_cd)) {
                $qb->whereIn("{$table}.genkin_cd", $request->genkin_cd);
            } else {
                $qb->where("{$table}.genkin_cd", $request->genkin_cd);
            }
        }

        if(!empty($request->unchin_mikakutei_kbn)) {
            if(is_array($request->unchin_mikakutei_kbn)) {
                $qb->whereIn("{$table}.unchin_mikakutei_kbn", $request->unchin_mikakutei_kbn);
            } else {
                $qb->where("{$table}.unchin_mikakutei_kbn", $request->unchin_mikakutei_kbn);
            }
        }

        if(empty($request->sort) && empty($request->dataMultiSort)) {
            $qb->orderBy('uriage_den_no', 'desc');
        }

        $arrayNotWhereInitSearch = [
            'hed_unso_dt_from',
            'hed_unso_dt_to',
            'unchin_mikakutei_kbn',
            'genkin_cd',
            'radio_hed_syaban',
            'hed_haitatu_dt_from',
            'hed_haitatu_dt_to',
            'hed_syuka_dt_from',
            'hed_syuka_dt_to',
            'hed_add_tanto_cd'
        ];
        foreach($keyInitSearch as $key => $value) {
            if(in_array($key, $arrayNotWhereInitSearch)) {
                continue;
            }
            if($request->filled($key)) {
                if(!empty($value['table'])) {
                    if(!empty($value['type_where']) && $value['type_where'] == 'like_after') {
                        if(!empty($value['column'])) {
                            $qb->where("{$value['table']}.{$value['column']}", 'ILIKE', makeEscapeStr($request->$key).'%');
                        } else {
                            $qb->where("{$value['table']}.{$key}", 'ILIKE', makeEscapeStr($request->$key).'%');
                        }
                        continue;
                    }
                    $cleanKey = preg_replace('/(_to|_from)$/', '', $key);
                    if(strpos($key, '_to') !== false) {
                        if(!empty($value['column'])) {
                            $qb->where("{$value['table']}.{$value['column']}", '<=', $request->$key);
                        } else {
                            $qb->where("{$value['table']}.{$cleanKey}", '<=', $request->$key);
                        }
                    } elseif(strpos($key, '_from') !== false) {
                       if(!empty($value['column'])) {
                            $qb->where("{$value['table']}.{$value['column']}", '>=', $request->$key);
                        } else {
                            $qb->where("{$value['table']}.{$cleanKey}", '>=', $request->$key);
                        }
                    } else {
                        if(!empty($value['column'])) {
                            $qb->where("{$value['table']}.{$value['column']}", $request->$key);
                        } else {
                            $qb->where("{$value['table']}.{$key}", $request->$key);
                        }
                    }
                    // $keyName = str_replace('_cd', '_nm', $key);
                    // $cleanKeyName = preg_replace('/(_to|_from)$/', '', $keyName);
                    // if(!empty($request->$keyName)) {
                    //     if(strpos($key, '_to') !== false) {
                    //         $qb->where("{$value['table']}.{$cleanKeyName}", '<=', $request->$keyName);
                    //     } else {
                    //         $qb->where("{$value['table']}.{$cleanKeyName}", '>=', $request->$keyName);
                    //     }
                    // }

                } else {
                    if(!empty($value['type_where']) && $value['type_where'] == 'like_after') {
                        if(!empty($value['column'])) {
                            $qb->where("{$table}.{$value['column']}", 'ILIKE', makeEscapeStr($request->$key).'%');
                        } else {
                            $qb->where("{$table}.{$key}", 'ILIKE', makeEscapeStr($request->$key).'%');
                        }
                        continue;
                    }
                    $cleanKey = preg_replace('/(_to|_from)$/', '', $key);
                    if(strpos($key, '_to') !== false) {
                        $qb->where("{$cleanKey}", '<=', $request->$key);
                    } elseif(strpos($key, '_from') !== false) {
                        $qb->where("{$cleanKey}", '>=', $request->$key);
                    } else {
                        $qb->where("{$cleanKey}", $request->$key);
                    }
                }
            }
        }
        if(!empty($request->dataMultiSort)) {
            if(is_array($request->dataMultiSort)) {
                foreach ($request->dataMultiSort as $key => $value) {
                    switch ($key) {
                        case 'hatuti_cd':
                            $qb->orderBy('m_hatuti.hachaku_cd', $value);
                            break;
                        case 'hatuti_nm':
                            $qb->orderBy('m_hatuti.hachaku_nm', $value);
                            break;
                        default:
                            $qb->orderBy($key, $value);

                            break;
                    }

                }
            }
        }

        $list = $qb;
        $total = $list->count();

        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function updateDataTable($request) {
        DB::beginTransaction();
        try {
            $dataTable = $request->list;
            if(!empty($dataTable)) {
                $fields = [
                    'unchin_kin',
                    'tyukei_kin',
                    'tukoryo_kin',
                    'syuka_kin',
                    'tesuryo_kin',
                    'syaryo_kin',
                    'unten_kin',
                    'yosya_tyukei_kin',
                    'yosya_kin_tax',
                    'su'
                ];
                foreach ($dataTable as $key => $item) {

                    foreach ($fields as $field) {
                        $value = $item[$field] ?? null;
                        if (!is_null($value)) {
                            $item[$field] = floatval(str_replace(',', '', $value));
                        }
                    }

                    if(!empty($item['uriage_den_no'])) {
                        if(!empty($item['del_flg'])) {
                            $uriage = TUriage::find($item['uriage_den_no']);
                            if($uriage->seikyu_no != null) {
                                DB::table('t_seikyu')->where('seikyu_no', $uriage->seikyu_no)->update(['seikyu_hako_flg' => 0, 'upd_dt' => \Carbon\Carbon::now()]);
                            }
                            $uriage->delete();
                        } else {
                            $uriage = TUriage::find($item['uriage_den_no']);
                            if(!empty($uriage)) {
                                if(empty($item['unchin_mikakutei_kbn'])) {
                                    $item['unchin_mikakutei_kbn'] = 0;

                                }
                                $item['yosya_kin_tax'] = null;
                                $item['yosya_kin_tax'] = null;
                                if(!empty($item['ninusi_cd'])) {
                                    $ninusi = DB::table('m_ninusi')->whereRaw(
                                            'ninusi_cd = (
                                                SELECT COALESCE(seikyu_cd, ninusi_cd) 
                                                FROM m_ninusi
                                                WHERE ninusi_cd = ?
                                            )', [$item['ninusi_cd']]
                                        )->first();
                                } else {
                                    $ninusi = null;
                                }
                                if(!empty($item['yousya_cd'])) {
                                    $yousya = DB::table('m_yousya')->whereRaw(
                                        'yousya_cd = (
                                            SELECT COALESCE(siharai_cd, yousya_cd) 
                                            FROM m_yousya
                                            WHERE yousya_cd = ?
                                        )', [$item['yousya_cd']])->first();
                                } else {
                                    $yousya = null;
                                }
                                if($uriage->menzei_kbn == 0) {
                                    $this->__calculatorRoundKinTax($ninusi, $yousya, $item);
                                }
                                if($uriage->seikyu_no != null) {
                                    DB::table('t_seikyu')->where('seikyu_no', $uriage->seikyu_no)->update(['seikyu_hako_flg' => 0, 'upd_dt' => \Carbon\Carbon::now()]);
                                }
                                if(!empty($item['haitatu_dt']) && !empty($item['ninusi_cd'])) {
                                    $this->__setSeikyuSimeDt($item);
                                }

                                $uriage->update($item);
                            }
                        }
                    } else {
                        $maxValue = TUriage::max('uriage_den_no');
                        $item['uriage_den_no'] = $maxValue + 1;
                        // if(request()->route()->getName() == 'uriage.uriage_entry.update_datatable') {
                            if(!empty($item['haitatu_dt']) && !empty($item['ninusi_cd'])) {
                                $this->__setSeikyuSimeDt($item);
                            }
                            if(empty($item['unchin_mikakutei_kbn'])) {
                                $item['unchin_mikakutei_kbn'] = 0;
                            }
                            $item['menzei_kbn'] = 0;
                            $item['yosya_kin_tax'] = null;
                            $item['seikyu_kin_tax'] = null;
                           
                        // }
                        if(!empty($item['ninusi_cd'])) {
                            $ninusi = DB::table('m_ninusi')->whereRaw(
                                            'ninusi_cd = (
                                                SELECT COALESCE(seikyu_cd, ninusi_cd) 
                                                FROM m_ninusi
                                                WHERE ninusi_cd = ?
                                            )', [$item['ninusi_cd']]
                                        )->first();
                        } else {
                            $ninusi = null;
                        }
                        if(!empty($item['yousya_cd'])) {
                            $yousya = DB::table('m_yousya')->whereRaw(
                                        'yousya_cd = (
                                            SELECT COALESCE(siharai_cd, yousya_cd) 
                                            FROM m_yousya
                                            WHERE yousya_cd = ?
                                        )', [$item['yousya_cd']])->first();
                        } else {
                            $yousya = null;
                        }
                        if($item['menzei_kbn'] == 0) {
                            $this->__calculatorRoundKinTax($ninusi, $yousya, $item);
                        }

                        $item['sime_kakutei_kbn'] = 0;
                        TUriage::create($item);
                    }
                }
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => [],
                'message' => trans('messages.updated2')
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => trans('messages.E0012')
            ];
        }
    }

    private function __setSeikyuSimeDt(&$item) {
        $date = \Carbon\Carbon::createFromFormat('Y/m/d', $item['haitatu_dt']);

        $day = $date->day;

        $addMonth = false;
        $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $item['ninusi_cd'] ?? '')->first();
        if(!empty($ninusi)) {
            if(!empty($ninusi->simebi1) && empty($ninusi->simebi2) && empty($ninusi->simebi3)) {
                $simebi = $ninusi->simebi1;
                if($day > $simebi) {
                    $addMonth = true;
                }
            } elseif(!empty($ninusi->simebi1) && !empty($ninusi->simebi2) && !empty($ninusi->simebi3)) {
                if($day <= $ninusi->simebi1) {
                    $simebi = $ninusi->simebi1;
                } elseif ($ninusi->simebi1 < $day && $day <= $ninusi->simebi2) {
                    $simebi = $ninusi->simebi2;
                    $addMonth = false;
                } elseif ($ninusi->simebi2 < $day && $day <= $ninusi->simebi3) {
                    $simebi = $ninusi->simebi3;
                    $addMonth = false;
                } else {
                    $simebi = $ninusi->simebi1;
                    $addMonth = true;
                }
            } elseif(!empty($ninusi->simebi1) && empty($ninusi->simebi2) && !empty($ninusi->simebi3)) {
                if($day <= $ninusi->simebi1) {
                    $simebi = $ninusi->simebi1;
                } elseif ($ninusi->simebi1 < $day && $day <= $ninusi->simebi3) {
                    $simebi = $ninusi->simebi3;
                    $addMonth = false;
                } else {
                    $simebi = $ninusi->simebi1;
                    $addMonth = true;
                }
            } elseif(!empty($ninusi->simebi1) && !empty($ninusi->simebi2) && empty($ninusi->simebi3)) {
                if($day <= $ninusi->simebi1) {
                    $simebi = $ninusi->simebi1;
                } elseif ($ninusi->simebi1 < $day && $day <= $ninusi->simebi2) {
                    $simebi = $ninusi->simebi2;
                    $addMonth = false;
                } else {
                    $simebi = $ninusi->simebi1;
                    $addMonth = true;
                }
            }
            if($addMonth == true) {
                $date->startOfMonth()->addMonth(1);
            }
            $month = $date->month;
            $year = $date->year;
            $lastDayOfMonth = $date->endOfMonth()->day;
            
            if(empty($ninusi->simebi1) && empty($ninusi->simebi2) && empty($ninusi->simebi3)) {
                $item['seikyu_sime_dt'] = null;
            } else {
                if($simebi > $lastDayOfMonth) {
                    $simebi = $lastDayOfMonth;
                }
                $item['seikyu_sime_dt'] = $year.'/'.$month.'/'.$simebi;
            }
        }

        $item['seikyu_keijyo_dt'] = $item['haitatu_dt'];
    }

    private function __calculatorRoundKinTax($ninusi, $yousya, &$item) {
        $item['yosya_kin_tax'] = null;
        $item['seikyu_kin_tax'] = null;
        if(!empty($yousya)) {
            if($yousya->zei_keisan_kbn == 3 && !empty($item['yosya_tyukei_kin'])) {
                $yousya = DB::table('m_yousya')->where('yousya_cd', $item['yousya_cd'])->first();
                $item['yosya_kin_tax'] = roundFromKbnTani($item['yosya_tyukei_kin'] * configParam('TAX_RATE'), intval($yousya->zei_hasu_kbn), intval($yousya->zei_hasu_tani));
            } 
        }
        if(!empty($ninusi)) {
            if($ninusi->zei_keisan_kbn == 3 && (!empty($item['unchin_kin']) || !empty($item['tyukei_kin']) || !empty($item['tesuryo_kin']) || !empty($item['nieki_kin']) || !empty($item['syuka_kin']) )) {
                $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $item['ninusi_cd'])->first();
                $item['seikyu_kin_tax'] = !empty($item['unchin_kin']) ? $item['unchin_kin'] : 0; 
                $item['seikyu_kin_tax'] += !empty($item['tyukei_kin']) ? $item['tyukei_kin'] : 0;
                $item['seikyu_kin_tax'] += !empty($item['tesuryo_kin']) ? $item['tesuryo_kin'] : 0;
                $item['seikyu_kin_tax'] += !empty($item['nieki_kin']) ? $item['nieki_kin'] : 0;
                $item['seikyu_kin_tax'] += !empty($item['syuka_kin']) ? $item['syuka_kin'] : 0;
                $item['seikyu_kin_tax'] = roundFromKbnTani($item['seikyu_kin_tax'] * configParam('TAX_RATE'), intval($ninusi->zei_hasu_kbn), intval($ninusi->zei_hasu_tani));
            }
        }
    }

    private function __getTableAndColumn() {
        return [
            'hatuti_cd' => [
                'table' => 'm_hatuti',
                'column' => 'hachaku_cd'
            ],
            'hatuti_nm' => [
                'table' => 'm_hatuti',
                'column' => 'hachaku_nm'
            ],
            'yousya_nm' => [
                'table' => 'm_yousya',
                'column' => 'yousya1_nm'
            ],
            'genkin_nm' => [
                'table' => 'm_meisyo_genkin',
                'column' => 'meisyo_nm'
            ]
        ];
    }
}
