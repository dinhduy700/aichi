<?php

namespace App\Http\Controllers;

use App\Models\MMeisyo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function searchSuggestion(Request $request)
    {
        $field = $request->field;
        $value = makeEscapeStr($request->value) .'%';
        $data = [];
        switch ($field) {
            case 'bumon_cd':
            case 'soko_bumon_cd':
                $data = DB::table('m_bumon')
                            ->select('*')
                            ->where('bumon_cd', 'ILIKE', strtolower($value))
                            ->orderBy('bumon_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'bumon_nm':
                $data = DB::table('m_bumon')
                            ->select('*')
                            ->orWhere(DB::raw('LOWER(bumon_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('bumon_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'ninusi_cd':
            case 'seikyu_cd':
            case 'atena_ninusi_id':
            case 'ninusi_id':
            case 'urikake_saki_cd':
            case 'soko_seikyu_cd':
                $data = DB::table('m_ninusi')
                            ->select(
                                '*',
                                DB::raw('ninusi_ryaku_nm as ninusi_nm'),
                            )
                            ->where('ninusi_cd', 'ILIKE', strtolower($value))
                            ->orderBy('ninusi_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'hachaku_cd':
                $data = DB::table('m_hachaku')
                            ->select('*')
                            ->where('hachaku_cd', 'ILIKE', strtolower($value))
                            ->orderBy('hachaku_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'hachaku_nm':
                $data = DB::table('m_hachaku')
                            ->select('*')
                            ->where(DB::raw('LOWER(hachaku_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('hachaku_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'hinmei_cd':
            case 'seikyu_hinmei_cd':
                $data = DB::table('m_hinmei')
                            ->select(
                                '*',
                                DB::raw('hinmei_cd as seikyu_hinmei_cd'),
                                DB::raw('hinmei_nm as seikyu_hinmei_nm'),
                            )
                            ->where('hinmei_cd', 'ILIKE', $value)
                            ->orderBy('hinmei_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'hinmei_nm':
            case 'hinmei_kana':
                $data = DB::table('m_hinmei')->select('*')
                            ->where(DB::raw('LOWER(hinmei_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('hinmei_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'ninusi_nm':
                $data = DB::table('m_ninusi')
                            ->select(
                                '*',
                                DB::raw('ninusi_ryaku_nm as ninusi_nm'),
                            )
                            ->where(DB::raw('LOWER(ninusi_ryaku_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('ninusi_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'hinmoku_cd':
                $data = DB::table('m_hinmoku')
                            ->select('*')
                            ->where('hinmoku_cd', 'ILIKE', strtolower($value))
                            ->orderBy('hinmoku_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'hinmoku_nm':
                $data = DB::table('m_hinmoku')
                            ->select('*')
                            ->where(DB::raw('LOWER(hinmoku_nm)'), 'ILIKE', strtolower($value))
                            ->orderBy('hinmoku_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;

            case 'yousya_ryaku_nm':
                $data = DB::table('m_yousya')
                            ->select('*')
                            ->where(DB::raw('LOWER(yousya_ryaku_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('yousya_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'yousya_cd':
            case 'siharai_cd':
            case 'kaikake_saki_cd':
                $data = DB::table('m_yousya')
                            ->select('*')
                            ->where('yousya_cd', 'ILIKE', strtolower($value))
                            ->orderBy('yousya_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;

            // 名称マスタ
            case 'tani_cd':
            case 'case_cd':
            case 'bara_tani':
                $data = DB::table('m_meisyo')
                            ->select(
                                '*',
                                DB::raw('meisyo_cd as tani_cd'),
                                DB::raw('meisyo_nm as tani_nm'),
                                DB::raw('meisyo_cd as case_cd'),
                                DB::raw('meisyo_nm as case_nm'),
                                DB::raw('meisyo_cd as bara_tani'),
                                DB::raw('meisyo_nm as bara_tani_nm'),
                            )
                            ->where([
                                ['meisyo_cd', 'ILIKE', $value],
                                ['meisyo_kbn', '=', configParam('MEISYO_KBN_TANI')],
                            ])->orderBy('meisyo_cd', 'DESC')->limit(configParam('LIMIT_SUGGEST'))->get();
                break;
            case 'tani_nm': 
                $data = DB::table('m_meisyo')
                        ->select(
                            '*',
                            DB::raw('meisyo_cd as tani_cd'),
                            DB::raw('meisyo_nm as tani_nm'),
                        )
                        ->where([
                            ['meisyo_nm', 'ILIKE', $value],
                            ['meisyo_kbn', '=', configParam('MEISYO_KBN_TANI')],
                        ])->orderBy('meisyo_cd', 'DESC')->limit(configParam('LIMIT_SUGGEST'))->get();
                break;
            case 'jyutyu_kbn':
                $data = MMeisyo::select('kana')->kbn(configParam('MEISYO_KBN_JYUTYU'), 'jyutyu_kbn', 'jyutyu_kbn_nm')
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'jyutyu_kbn_nm':
                $data = MMeisyo::select('kana')->kbn(configParam('MEISYO_KBN_JYUTYU'), 'jyutyu_kbn', 'jyutyu_kbn_nm')
                    ->where(function ($query) use ($value) {
                        $query->where('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
            case 'jyomuin_cd':
                $data = DB::table('m_jyomuin')
                            ->select('*')
                            ->where('jyomuin_cd', 'ILIKE', $value)
                            ->orderBy('jyomuin_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'jyomuin_nm':
                $data = DB::table('m_jyomuin')->select('*')
                            ->orWhere(DB::raw('LOWER(jyomuin_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('jyomuin_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'syasyu_cd':
                $data = DB::table('m_meisyo')->select('*')
                    ->where([
                        ['meisyo_cd', 'ILIKE', $value],
                        ['meisyo_kbn', '=', configParam('MEISYO_KBN_SYASYU')],
                    ])
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'rikuun_cd':
                $data = DB::table('m_meisyo')->select('*')
                    ->where([
                        ['meisyo_cd', 'ILIKE', $value],
                        ['meisyo_kbn', '=', configParam('MEISYO_KBN_RIKUUN')],
                    ])
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'biko_cd':
                $data = DB::table('m_biko')
                            ->select('*')
                            ->where('biko_cd', 'ILIKE', $value)
                            ->orderBy('biko_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'biko_nm':
                $data = DB::table('m_biko')->select('*')
                            ->where(DB::raw('LOWER(biko_nm)'), 'ILIKE', strtolower($value))
                            ->orWhere(DB::raw('LOWER(kana)'), 'ILIKE', strtolower($value))
                            ->orderBy('biko_cd', 'DESC')
                            ->limit(configParam('LIMIT_SUGGEST'))
                            ->get();
                break;
            case 'soko_cd':
                $data = DB::table('m_soko')->select('*')
                    ->where([
                        ['soko_cd', 'ILIKE', strtolower($value)]
                    ])
                    ->orderBy('soko_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'soko_nm':
                $data = DB::table('m_soko')->select('*')
                    ->where([
                        [DB::raw('LOWER(soko_nm)'), 'ILIKE', strtolower($value)]
                    ])
                    ->orderBy('soko_nm', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'soko_hinmei_cd':
                $data = DB::table('m_soko_hinmei')->distinct()
                    ->select(['hinmei_cd', 'kana', 'hinmei_nm'])
                    ->where('hinmei_cd', 'ILIKE', $value)
                    ->orderBy('hinmei_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'soko_hinmei_nm':
                $data = DB::table('m_soko_hinmei')->distinct()
                    ->select(['hinmei_cd', 'kana', 'hinmei_nm'])
                    ->where('hinmei_nm', 'ILIKE', $value)
                    ->orderBy('hinmei_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hatuti_cd':
                $data = DB::table('m_hachaku')
                    ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'))
                    ->where('hachaku_cd', 'ILIKE', $value)
                    ->orderBy('hachaku_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hatuti_nm':
                $data = \DB::table('m_hachaku')
                    ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'))
                    ->where(function ($query) use ($value) {
                        $query->where('hachaku_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('hachaku_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
}
