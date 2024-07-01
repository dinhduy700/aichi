<?php 
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use App\Models\TNyusyukoMeisai;
use App\Models\TNyusyukoHead;
use App\Models\TZaiko;
use App\Models\MNinusi;
use App\Models\TUriage;
use App\Models\TZaikoNyusyukoMeisai;
use Auth;
use DB;

class NyusyukoNyuryokuRepository 
{
    public function getListWithTotalCount($request, $nyusyukoHead) 
    {
        $qb = DB::table('t_nyusyuko_head as nyuh');
        if($nyusyukoHead->nyusyuko_kbn != 5) {
            $qb->select(
                'nyuh.*',
                'nyum.nyusyuko_den_meisai_no',
                'nyum.hinmei_cd',
                'nyum.case_su',
                'nyum.hasu',
                'nyum.su',
                'nyum.jyuryo',
                'nyum.soko_cd',
                'nyum.tani_cd',
                'nyum.location',
                'nyum.biko',
                'nyum.nyuko_dt',
                'nyum.seizo_no',
                'nyum.situryo',
                'nyum.lot1',
                'nyum.lot2',
                'nyum.lot3',
                'm_soko_hinmei.kikaku',
                'm_soko_hinmei.irisu',
                'm_soko_hinmei.hinmei_nm',
                DB::raw('tani.meisyo_nm as tani_nm'),
                'soko.soko_nm',
                'm_ninusi.ninusi_ryaku_nm',
                'nyuh.hatuti_nm',
                'm_ninusi.ninusi1_nm',
                'm_ninusi.ninusi2_nm',
                'nyum.biko',
                'm_jyomuin.jyomuin_nm'
            );
        } else {
            // $nyusyukoDenNo = $recordHead['nyusyuko_den_no'];
            $qb->select(
                'nyuh.*',
                'nyum.nyusyuko_den_meisai_no',
                'nyum.hinmei_cd',
                DB::raw('ABS(nyum.case_su) as case_su'),
                DB::raw('ABS(nyum.hasu) as hasu'),
                DB::raw('ABS(nyum.su) as su'),
                DB::raw('ABS(nyum.jyuryo) as jyuryo'),
                'nyum.soko_cd',
                'nyum.tani_cd',
                'nyum.location',
                'nyum.biko',
                'nyum.nyuko_dt',
                'nyum.seizo_no',
                'nyum.situryo',
                'nyum.lot1',
                'nyum.lot2',
                'nyum.lot3',
                'm_soko_hinmei.kikaku',
                'm_soko_hinmei.irisu',
                'm_soko_hinmei.hinmei_nm',
                DB::raw('tani.meisyo_nm as tani_nm'),
                'soko.soko_nm',
                DB::raw("
                    (
                        SELECT meisai_to.soko_cd 
                        FROM t_nyusyuko_meisai as meisai_to
                        WHERE meisai_to.nyusyuko_den_no = nyum.nyusyuko_den_no
                        AND (meisai_to.lot1 = nyum.lot1 OR (meisai_to.lot1 IS NULL AND nyum.lot1 IS NULL))
                        AND (meisai_to.lot2 = nyum.lot2 OR (meisai_to.lot2 IS NULL AND nyum.lot2 IS NULL))
                        AND (meisai_to.lot3 = nyum.lot3 OR (meisai_to.lot3 IS NULL AND nyum.lot3 IS NULL))
                        AND meisai_to.hinmei_cd = nyum.hinmei_cd
                        AND meisai_to.su > 0 AND meisai_to.nyusyuko_den_meisai_no = nyum.nyusyuko_den_meisai_no + 1
                        LIMIT 1
                    ) as soko_cd_to
                "),
                DB::raw("
                    (
                        SELECT m_soko_to.soko_nm 
                        FROM t_nyusyuko_meisai as meisai_to
                        LEFT JOIN m_soko as m_soko_to ON m_soko_to.soko_cd = meisai_to.soko_cd
                        WHERE meisai_to.nyusyuko_den_no = nyum.nyusyuko_den_no
                        AND (meisai_to.lot1 = nyum.lot1 OR (meisai_to.lot1 IS NULL AND nyum.lot1 IS NULL))
                        AND (meisai_to.lot2 = nyum.lot2 OR (meisai_to.lot2 IS NULL AND nyum.lot2 IS NULL))
                        AND (meisai_to.lot3 = nyum.lot3 OR (meisai_to.lot3 IS NULL AND nyum.lot3 IS NULL))
                        AND meisai_to.hinmei_cd = nyum.hinmei_cd
                        AND meisai_to.su > 0 AND meisai_to.nyusyuko_den_meisai_no = nyum.nyusyuko_den_meisai_no + 1
                        AND m_soko_to.bumon_cd = nyuh.bumon_cd
                        LIMIT 1
                    ) as soko_nm_to
                "),
                DB::raw("
                    (
                        SELECT meisai_to.location 
                        FROM t_nyusyuko_meisai as meisai_to
                        WHERE meisai_to.nyusyuko_den_no = nyum.nyusyuko_den_no
                        AND (meisai_to.lot1 = nyum.lot1 OR (meisai_to.lot1 IS NULL AND nyum.lot1 IS NULL))
                        AND (meisai_to.lot2 = nyum.lot2 OR (meisai_to.lot2 IS NULL AND nyum.lot2 IS NULL))
                        AND (meisai_to.lot3 = nyum.lot3 OR (meisai_to.lot3 IS NULL AND nyum.lot3 IS NULL))
                        AND meisai_to.hinmei_cd = nyum.hinmei_cd
                        AND meisai_to.su > 0 AND meisai_to.nyusyuko_den_meisai_no = nyum.nyusyuko_den_meisai_no + 1
                        LIMIT 1
                    ) as location_to
                ")
            );
        }
        $qb->join('t_nyusyuko_meisai as nyum', 'nyum.nyusyuko_den_no', 'nyuh.nyusyuko_den_no')
            // ->leftjoin('m_hinmei as hm', 'hm.hinmei_cd', 'nyum.hinmei_cd')
            ->leftjoin('m_soko_hinmei', function($join) {
                $join->on('m_soko_hinmei.hinmei_cd', 'nyum.hinmei_cd');
                $join->on('m_soko_hinmei.ninusi_cd', 'nyuh.ninusi_cd');
            })
            ->leftjoin('m_meisyo as tani', function($join) {
                $join->on('tani.meisyo_cd', 'nyum.tani_cd');
                $join->where('tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
            })
            ->leftjoin('m_soko as soko', function($join) {
                $join->on('soko.soko_cd', 'nyum.soko_cd');
                $join->on('soko.bumon_cd', 'nyuh.bumon_cd');
            })
            ->leftjoin('m_ninusi', 'm_ninusi.ninusi_cd', 'nyuh.ninusi_cd')
            ->leftjoin('m_hachaku', 'm_hachaku.hachaku_cd', 'nyuh.hachaku_cd')
            ->leftjoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 'nyuh.jyomuin_cd')
            // ->orderBy('nyuh.nyusyuko_den_no')
            ->orderBy('nyum.nyusyuko_den_meisai_no');
            
        if(!empty($nyusyukoHead))
        {
            $qb->where('nyuh.nyusyuko_den_no', $nyusyukoHead->nyusyuko_den_no);
        }

        if($nyusyukoHead->nyusyuko_kbn == 5) {
            $qb->where('nyum.su', '<', 0);
        }

        // if($request->filled('hed_bumon_cd'))
        // {
        //     $qb->where('nyuh.bumon_cd', '=', $request->hed_bumon_cd);
        // }

        $list = $qb;
        $total = $list->count();

        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function getListWithTotalCountHead($request) 
    {   
        $qb = DB::table('t_nyusyuko_head')
            ->select(
                't_nyusyuko_head.*',
                'm_ninusi.ninusi_ryaku_nm',
                't_nyusyuko_meisai.lot1',
                't_nyusyuko_meisai.lot2',
                't_nyusyuko_meisai.lot3',
                't_nyusyuko_meisai.su',
                't_nyusyuko_meisai.biko',
                'm_soko_hinmei.hinmei_nm',
                DB::raw('tani.meisyo_nm as tani_nm'),
                'm_soko_hinmei.kikaku', 
            )
            ->leftJoin('t_nyusyuko_meisai', 't_nyusyuko_meisai.nyusyuko_den_no', 't_nyusyuko_head.nyusyuko_den_no')
            ->leftjoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_nyusyuko_head.ninusi_cd')
            // ->leftjoin('m_hinmei', 'm_hinmei.hinmei_cd', 't_nyusyuko_meisai.hinmei_cd')
            ->leftjoin('m_soko_hinmei', function($join) {
                $join->on('m_soko_hinmei.hinmei_cd', 't_nyusyuko_meisai.hinmei_cd');
                $join->on('m_soko_hinmei.ninusi_cd', 't_nyusyuko_head.ninusi_cd');
            })
            ->leftjoin('m_meisyo as tani', function($join) {
                $join->on('tani.meisyo_cd', 't_nyusyuko_meisai.tani_cd');
                $join->where('tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
            });

        if($request->filled('hed_bumon_cd')) {
            $qb->where('t_nyusyuko_head.bumon_cd', 'ilike', makeEscapeStr($request->hed_bumon_cd) .'%');
        }

        if($request->filled('hed_nyusyuko_kbn')) {
            $qb->where('t_nyusyuko_head.nyusyuko_kbn', $request->hed_nyusyuko_kbn);
        }

        if($request->filled('denpyo_dt_from')) {
            $qb->where('t_nyusyuko_head.denpyo_dt', '>=', $request->denpyo_dt_from);
        }

        if($request->filled('denpyo_dt_to')) {
            $qb->where('t_nyusyuko_head.denpyo_dt', '<=', $request->denpyo_dt_from);
        }

        if($request->filled('todokesaki_nm')) {
            if($request->todokesaki_nm_jyoken == 1) {
                $qb->where('t_nyusyuko_head.todokesaki_nm', 'ilike', '%'.makeEscapeStr($request->todokesaki_nm).'%');
            } else {
                $qb->where('t_nyusyuko_head.todokesaki_nm', 'ilike', makeEscapeStr($request->todokesaki_nm).'%');
            }
        }

        if($request->filled('nyusyuko_den_no_from')) {
            $qb->where('t_nyusyuko_head.nyusyuko_den_no', '>=', $request->nyusyuko_den_no_from);
        }

        if($request->filled('nyusyuko_den_no_to')) {
            $qb->where('t_nyusyuko_head.nyusyuko_den_no', '<=', $request->nyusyuko_den_no_to);
        }

        if($request->filled('hinmei_nm')) {
            if($request->hinmei_nm_jyoken == 1) {
                $qb->where('m_soko_hinmei.hinmei_nm', 'ilike', '%'.makeEscapeStr($request->hinmei_nm).'%');
            } else {
                $qb->where('m_soko_hinmei.hinmei_nm', 'ilike', makeEscapeStr($request->hinmei_nm).'%');
            }
        }

        if($request->filled('ninusi_cd_from')) {
            $qb->where('t_nyusyuko_head.ninusi_cd', '>=', $request->ninusi_cd_from);
        }

        if($request->filled('ninusi_cd_to')) {
            $qb->where('t_nyusyuko_head.ninusi_cd', '<=', $request->ninusi_cd_to);
        }

        if($request->filled('hachaku_cd_from')) {
            $qb->where('t_nyusyuko_head.hachaku_cd', '>=', $request->hachaku_cd_from);
        }

        if($request->filled('hachaku_cd_to')) {
            $qb->where('t_nyusyuko_head.hachaku_cd', '<=', $request->hachaku_cd_to);
        }

        $list = $qb;
        $total = $list->count();
        return [
            'total' => $total,
            'rows' => $list,
        ];
    } 

    public function updateData($request) {
        $hasTazai = true;
        DB::beginTransaction();
        try {
            $nyusyukoHead = $request->nyusyuko_head;
            $nyusyukoMeisai = $request->nyusyuko_meisai;
            $uriage = $request->uriage;
            $nyusyukoDenNo = null;
            $flgInsert = false;
            if(!empty($nyusyukoHead)) {
                if(!empty($nyusyukoHead['nyusyuko_den_no'])) {
                    $nyusyukoDenNo = $nyusyukoHead['nyusyuko_den_no'] ?? '';
                    $recordHead = TNyusyukoHead::find($nyusyukoDenNo);
                    if(!empty($recordHead)) {
                        $recordHead->update($nyusyukoHead);
                    }
                } else {
                    $totalMax = TNyusyukoHead::max('nyusyuko_den_no');
                    $nyusyukoHead['nyusyuko_den_no'] = $totalMax + 1;
                    $maxOkurijyoNoResult  = TNyusyukoHead::select(DB::raw('MAX(CAST(okurijyo_no AS INTEGER)) as max_okurijyo_no'))->first();
                    $maxOkurijyoNo = $maxOkurijyoNoResult ? $maxOkurijyoNoResult->max_okurijyo_no : null;
                    $nextOkurijyoNo = $maxOkurijyoNo !== null ? $maxOkurijyoNo + 1 : 1;
                    $nyusyukoHead['okurijyo_no'] = $nextOkurijyoNo;

                    $recordHead = TNyusyukoHead::create($nyusyukoHead);
                    $flgInsert = true;
                    $nyusyukoDenNo = $recordHead->nyusyuko_den_no;
                }
            }

            if(!empty($nyusyukoMeisai) && !empty($nyusyukoDenNo)) {
                $ninusi = MNinusi::where('ninusi_cd', $recordHead->ninusi_cd)->first();
                foreach ($nyusyukoMeisai as $key => $meisai) {
                    if(!empty($meisai['nyusyuko_den_meisai_no'])) {
                        if($recordHead->nyusyuko_kbn == 5) {
                            $recordMeisaiFrom = TNyusyukoMeisai::where([
                                                    ['nyusyuko_den_meisai_no', '=', $meisai['nyusyuko_den_meisai_no']],
                                                    ['nyusyuko_den_no', '=', $meisai['nyusyuko_den_no']],
                                                ])->first();
                            $sokoHinmei = DB::table('m_soko_hinmei')->where([
                                ['ninusi_cd', '=', $ninusi->ninusi_cd],
                                ['hinmei_cd', '=', $recordMeisaiFrom->hinmei_cd]
                            ])->first();
                            $irisu = $sokoHinmei->irisu;
                            $this->__deleteTZaikoCaseKbn5($recordHead, $recordMeisaiFrom, $ninusi, 1, $irisu);
                            $recordMeisaiTo = TNyusyukoMeisai::where([
                                                ['nyusyuko_den_meisai_no', '=', $meisai['nyusyuko_den_meisai_no'] + 1],
                                                ['nyusyuko_den_no', '=', $meisai['nyusyuko_den_no'] ],
                                            ])->first();
                            $this->__deleteTZaikoCaseKbn5($recordHead, $recordMeisaiTo, $ninusi, 2, $irisu);
                            if(!empty($meisai['del_flg'])) {
                                DB::table('t_zaiko_nyusyuko_meisai')->where('nyusyuko_den_no', $recordHead->nyusyuko_den_no)->where('nyusyuko_den_meisai_no', $recordMeisaiFrom->nyusyuko_den_meisai_no)->delete();
                                $recordMeisaiFrom->delete();
                                DB::table('t_zaiko_nyusyuko_meisai')->where('nyusyuko_den_no', $recordHead->nyusyuko_den_no)->where('nyusyuko_den_meisai_no', $recordMeisaiTo->nyusyuko_den_meisai_no)->delete();
                                $recordMeisaiTo->delete();
                                continue;
                            }
                            $meisai['lot1'] = ($ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot1', $meisai) && $meisai['lot1'] !== null ? $meisai['lot1'] : '';
                            $meisai['lot2'] = ($ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot2', $meisai) && $meisai['lot2'] !== null ? $meisai['lot2'] : '';
                            $meisai['lot3'] = ($ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot3', $meisai) && $meisai['lot3'] !== null ? $meisai['lot3'] : '';
                            $meisai['location'] = array_key_exists('location', $meisai) && $meisai['location'] !== null ? $meisai['location'] : '';
                            $meisaiFrom = $meisai;
                            $irisu = !empty($meisai['irisu']) ? $meisai['irisu'] : 1;
                            $meisaiFrom['su'] =  $meisaiFrom['su'] * (-1);
                            if($meisaiFrom['su'] >= 0) {
                                $meisaiFrom['case_su'] = floor($meisaiFrom['su'] / $irisu) ;
                            } else {
                                $meisaiFrom['case_su'] = ceil($meisaiFrom['su'] / $irisu) ;
                            }
                            $meisaiFrom['hasu'] =  $meisaiFrom['su'] % $irisu;
                            $meisaiFrom['jyuryo'] = -1 * $meisaiFrom['jyuryo'];
                            $recordMeisaiFrom->update($meisaiFrom);

                            $this->__updateTzaikoCaseKbn5($recordHead, $recordMeisaiFrom, $ninusi, 2, $irisu);
                            $meisai['location'] = array_key_exists('location_to', $meisai) && $meisai['location_to'] !== null ? $meisai['location_to'] : '';
                            $meisai['nyusyuko_den_meisai_no'] = $meisai['nyusyuko_den_meisai_no'] + 1;
                            $meisai['soko_cd'] = $meisai['soko_cd_to'];

                            if($meisai['su'] >= 0) {
                                $meisai['case_su'] = floor($meisai['su'] / $irisu) ;
                            } else {
                                $meisai['case_su'] = ceil($meisai['su'] / $irisu) ;
                            }
                            $meisai['hasu'] =  $meisai['su'] % $irisu;

                            $recordMeisaiTo->update($meisai);

                            $this->__updateTzaikoCaseKbn5($recordHead, $recordMeisaiTo, $ninusi, 1, $irisu);

                            continue;
                        }
                        // Update nyusyuko_meisai
                        $recordMeisai = TNyusyukoMeisai::where([
                            ['nyusyuko_den_meisai_no', '=', $meisai['nyusyuko_den_meisai_no']],
                            ['nyusyuko_den_no', '=', $meisai['nyusyuko_den_no']],
                        ])->first();

                        $sokoHinmei = DB::table('m_soko_hinmei')->where([
                            ['ninusi_cd', '=', $ninusi->ninusi_cd],
                            ['hinmei_cd', '=', $recordMeisai->hinmei_cd]
                        ])->first();

                        if(!empty($recordMeisai)) {
                            if(!empty($meisai['del_flg'])) {
                                $zaikoDelete = TZaiko::where([
                                    ['hinmei_cd', '=', $recordMeisai->hinmei_cd],
                                    ['soko_cd', '=', $recordMeisai->soko_cd],
                                    ['location', '=', $recordMeisai->location],
                                    ['bumon_cd', '=', $recordHead->bumon_cd],
                                    ['ninusi_cd', '=', $recordHead->ninusi_cd]
                                ]);
                                $this->__appendQueryLot($ninusi, $recordMeisai, $zaikoDelete);
                                $zaikoDelete = $zaikoDelete->first();
                                if(!empty($zaikoDelete)) {
                                    $su = $recordHead->nyusyuko_kbn == 1 || $recordHead->nyusyuko_kbn == 4 ? ($zaikoDelete->su - $recordMeisai->su) : ($zaikoDelete->su + $recordMeisai->su);
                                    $irisu = !empty($sokoHinmei) ? ( $sokoHinmei->irisu ?? 1 ) : 1;
                                    if($su >= 0) {
                                        $caseSu = floor($su / $irisu);
                                    } else {
                                        $caseSu = ceil($su / $irisu);
                                    }
                                    $hasu =  $su % $irisu;
                                    $zaikoDelete->update([
                                        'su' => $su,
                                        'hasu' => $hasu,
                                        'case_su' => $caseSu
                                    ]);
                                } 
                                DB::table('t_zaiko_nyusyuko_meisai')->where('nyusyuko_den_no', $recordHead->nyusyuko_den_no)->where('nyusyuko_den_meisai_no', $recordMeisai->nyusyuko_den_meisai_no)->delete();
                                $recordMeisai->delete();
                            } else {
                                $zaikoDelete = TZaiko::where([
                                    ['hinmei_cd', '=', $recordMeisai->hinmei_cd],
                                    ['soko_cd', '=', $recordMeisai->soko_cd],
                                    ['location', '=', $recordMeisai->location],
                                    ['bumon_cd', '=', $recordHead->bumon_cd],
                                    ['ninusi_cd', '=', $recordHead->ninusi_cd]
                                ]);
                                $this->__appendQueryLot($ninusi, $recordMeisai, $zaikoDelete);
                                $zaikoDelete = $zaikoDelete->first();

                                if(!empty($zaikoDelete)) {
                                    $su = $recordHead->nyusyuko_kbn == 1 || $recordHead->nyusyuko_kbn == 4 ? ($zaikoDelete->su - $recordMeisai->su) : ($zaikoDelete->su + $recordMeisai->su);
                                    $irisu = !empty($sokoHinmei) ? ( $sokoHinmei->irisu ?? 1 ) : 1;
                                    if($su >= 0) {
                                        $caseSu = floor($su / $irisu);
                                    } else {
                                        $caseSu = ceil($su / $irisu);
                                    }
                                    $hasu =  $su % $irisu;

                                    $zaikoDelete->update([
                                        'su' => $su,
                                        'hasu' => $hasu,
                                        'case_su' => $caseSu
                                    ]);
                                }
                                $irisu = !empty($sokoHinmei) ? ( $sokoHinmei->irisu ?? 1 ) : 1;
                                $meisai['hasu'] = $meisai['su'] % $irisu;
                                if($meisai['su'] >= 0) {
                                    $meisai['case_su'] = floor($meisai['su'] / $irisu);
                                } else {
                                    $meisai['case_su'] = ceil($meisai['su'] / $irisu);
                                }
                                
                                $meisai['lot1'] = ($ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot1', $meisai) && $meisai['lot1'] !== null ? $meisai['lot1'] : '';
                                $meisai['lot2'] = ($ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot2', $meisai) && $meisai['lot2'] !== null ? $meisai['lot2'] : '';
                                $meisai['lot3'] = ($ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot3', $meisai) && $meisai['lot3'] !== null ? $meisai['lot3'] : '';
                                $meisai['location'] = array_key_exists('location', $meisai) && $meisai['location'] !== null ? $meisai['location'] : '';
                                $recordMeisai->update($meisai);
                                $zaikoUpdate = TZaiko::where([
                                    ['hinmei_cd', '=', $meisai['hinmei_cd']],
                                    ['soko_cd', '=', $meisai['soko_cd']],
                                    ['location', '=', $meisai['location']],
                                    ['bumon_cd', '=', $recordHead->bumon_cd],
                                    ['ninusi_cd', '=', $recordHead->ninusi_cd]
                                ]);

                                $this->__appendQueryLot($ninusi, $recordMeisai, $zaikoUpdate);
                                $zaikoUpdate = $zaikoUpdate->first();

                                if(empty($zaikoUpdate)) {
                                    $dataZaiko = [
                                        'bumon_cd' => $recordHead->bumon_cd,
                                        'hinmei_cd' => $meisai['hinmei_cd'],
                                        'soko_cd' => $meisai['soko_cd'],
                                        'location' => $meisai['location'],
                                        'lot1' => $ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot1 : '',
                                        'lot2' => $ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot2 : '',
                                        'lot3' => $ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot3 : '',
                                        'case_su' => null,
                                        'hasu' => null,
                                        'su' => null
                                    ];
                                    $dataZaikoInsert = $dataZaiko;
                                    $dataZaikoInsert['seq_no'] = TZaiko::max('seq_no') + 1;
                                    $dataZaikoInsert['bumon_cd'] = $recordHead->bumon_cd;
                                    $dataZaikoInsert['ninusi_cd'] = $recordHead->ninusi_cd;
                                    $zaikoUpdate = TZaiko::create($dataZaikoInsert);
                                    $seq = TZaikoNyusyukoMeisai::max('seq_no');
                                    TZaikoNyusyukoMeisai::insert([
                                        'seq_no' => $seq + 1,
                                        'zaiko_seq_no' => $zaikoUpdate->seq_no,
                                        'nyusyuko_den_no' => $nyusyukoDenNo,
                                        'nyusyuko_den_meisai_no' => $recordMeisai->nyusyuko_den_meisai_no,
                                        'add_user_cd' => Auth::id(),
                                        'upd_user_cd' => Auth::id(),
                                        'add_dt' => \Carbon\Carbon::now(),
                                        'upd_dt' => \Carbon\Carbon::now(),
                                    ]);
                                }
                                $su = $recordHead->nyusyuko_kbn == 1 || $recordHead->nyusyuko_kbn == 4 ? ($zaikoUpdate->su + $meisai['su']) : ($zaikoUpdate->su - $meisai['su']);
                                
                                if($su >= 0) {
                                    $caseSu = floor($su / $irisu);
                                } else {
                                    $caseSu = ceil($su / $irisu);
                                }
                                $hasu =  $su % $irisu;
                                
                                $zaikoUpdate->update([
                                    'su' => $su,
                                    'hasu' => $hasu,
                                    'case_su' => $caseSu
                                ]);
                               
                                
                            }
                        }

                    } else {
                        if(!empty($meisai['del_flg'])) {
                            continue;
                        }
                        $meisai['nyusyuko_den_no'] = $nyusyukoDenNo;
                        $totalMax = TNyusyukoMeisai::where([
                            ['nyusyuko_den_no', '=', $meisai['nyusyuko_den_no']],
                        ])->max('nyusyuko_den_meisai_no');
                        $meisai['nyusyuko_den_meisai_no'] = $totalMax + 1;
                        $meisai['lot1'] = ($ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot1', $meisai) && $meisai['lot1'] !== null ? $meisai['lot1'] : '';
                        $meisai['lot2'] = ($ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot2', $meisai) && $meisai['lot2'] !== null ? $meisai['lot2'] : '';
                        $meisai['lot3'] = ($ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null) && array_key_exists('lot3', $meisai) && $meisai['lot3'] !== null ? $meisai['lot3'] : '';
                        $meisai['location'] = array_key_exists('location', $meisai) && $meisai['location'] !== null ? $meisai['location'] : '';
                        if($recordHead->nyusyuko_kbn == 5) {
                            $irisu = !empty($meisai['irisu']) ? $meisai['irisu'] : 1;
                            $meisaiFrom = $meisai;

                            $meisaiFrom['su'] =  $meisaiFrom['su'] * (-1);
                            if($meisaiFrom['su'] >= 0) {
                                $meisaiFrom['case_su'] =  floor($meisaiFrom['su'] / $irisu);
                            } else {
                                $meisaiFrom['case_su'] =  ceil($meisaiFrom['su'] / $irisu);
                            }
                            $meisaiFrom['hasu'] =  $meisaiFrom['su'] % $irisu;
                            $meisaiFrom['jyuryo'] = -1 * $meisaiFrom['jyuryo'];
                            $recordMeisaiFrom = TNyusyukoMeisai::create($meisaiFrom);
                            $this->__createTZaikoCaseKbn5($recordHead, $recordMeisaiFrom, $ninusi, 2, $irisu);

                            $meisaiTo = $meisai;
                            $meisaiTo['soko_cd'] = $meisai['soko_cd_to'];
                            $meisaiTo['location'] = array_key_exists('location_to', $meisai) && $meisai['location_to'] !== null ? $meisai['location_to'] : '';
                            $meisaiTo['location'] = !empty($meisaiTo['location']) || $meisaiTo['location'] === 0 || $meisaiTo['location'] === '0' ? $meisaiTo['location'] : '';
                            if($meisaiTo['su'] >= 0) {
                                $meisaiTo['case_su'] =  floor($meisaiTo['su'] / $irisu);
                            } else {
                                $meisaiTo['case_su'] =  ceil($meisaiTo['su'] / $irisu);
                            }
                            $meisaiTo['hasu'] =  $meisaiTo['su'] % $irisu;
                            $meisaiTo['nyusyuko_den_meisai_no'] = $meisai['nyusyuko_den_meisai_no'] + 1;
                            $recordMeisaiTo = TNyusyukoMeisai::create($meisaiTo);
                            $this->__createTZaikoCaseKbn5($recordHead, $recordMeisaiTo, $ninusi, 1, $irisu);
                            continue;
                        }
                        $irisu = !empty($meisai['irisu']) ? $meisai['irisu'] : 1;
                        if($meisai['su'] >= 0) {
                            $meisai['case_su'] =  floor($meisai['su'] / $irisu);
                        } else {
                            $meisai['case_su'] =  ceil($meisai['su'] / $irisu);
                        }
                        $meisai['hasu'] =  $meisai['su'] % $irisu;
                        $recordMeisai =  TNyusyukoMeisai::create($meisai);
                        
                        $dataZaiko = [
                            'bumon_cd' => $recordHead->bumon_cd,
                            'hinmei_cd' => $recordMeisai->hinmei_cd,
                            'soko_cd' => $recordMeisai->soko_cd,
                            'location' => $recordMeisai->location,
                            'lot1' => $ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot1 : '',
                            'lot2' => $ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot2 : '',
                            'lot3' => $ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot3 : '',
                            'case_su' => $recordMeisai->case_su,
                            'hasu' => $recordMeisai->hasu,
                            'su' => $recordMeisai->su
                        ];

                        $dataZaikoInsert = $dataZaiko;
                        $dataZaikoInsert['seq_no'] = TZaiko::max('seq_no') + 1;

                        $zaiko = TZaiko::where([
                            ['hinmei_cd','=', $dataZaiko['hinmei_cd']],
                            ['soko_cd','=', $dataZaiko['soko_cd']],
                            ['location','=', $dataZaiko['location']],
                            ['bumon_cd', '=', $recordHead->bumon_cd],
                            ['ninusi_cd', '=', $recordHead->ninusi_cd]
                        ]);

                        $this->__appendQueryLot($ninusi, $recordMeisai, $zaiko);
                        $zaiko = $zaiko->first();
                        if(empty($zaiko)) {
                            if($recordHead->nyusyuko_kbn == 2) {
                                $dataZaikoInsert['su'] = $dataZaikoInsert['su'] * (-1);
                            }
                            $dataZaikoInsert['bumon_cd'] = $recordHead->bumon_cd;
                            $dataZaikoInsert['ninusi_cd'] = $recordHead->ninusi_cd;
                            $zaiko = TZaiko::create($dataZaikoInsert);
                            $su = $zaiko->su;
                        } else {
                            if($recordHead->nyusyuko_kbn == 4) {
                                $su = $zaiko->su + $dataZaiko['su'];
                            } elseif($recordHead->nyusyuko_kbn == 2) {
                                $su = $zaiko->su - $dataZaiko['su'];
                            } else {
                                $su = $zaiko->su + $dataZaiko['su'];
                            }
                        }

                        $irisu = !empty($meisai['irisu']) ? $meisai['irisu'] : 1;
                        if($su >= 0) {
                            $caseSu = floor($su / $irisu);
                        } else {
                            $caseSu = ceil($su / $irisu);
                        }
                        $hasu =  $su % $irisu;

                        $zaiko->update([
                            'su' => $su,
                            'case_su' => $caseSu,
                            'hasu' => $hasu
                        ]);

                        $seq = DB::table('t_zaiko_nyusyuko_meisai')->max('seq_no');
                        DB::table('t_zaiko_nyusyuko_meisai')->insert([
                            'seq_no' => $seq + 1,
                            'zaiko_seq_no' => $zaiko->seq_no,
                            'nyusyuko_den_no' => $nyusyukoDenNo,
                            'nyusyuko_den_meisai_no' => $recordMeisai->nyusyuko_den_meisai_no,
                            'add_user_cd' => Auth::id(),
                            'upd_user_cd' => Auth::id(),
                            'add_dt' => \Carbon\Carbon::now(),
                            'upd_dt' => \Carbon\Carbon::now(),
                        ]);
                    }
                }
            }

            if(!empty($uriage) && !empty($nyusyukoDenNo)) {
                if(!empty($uriage['uriage_den_no'])) {
                    $uriageDB = TUriage::where('uriage_den_no', $uriage['uriage_den_no'])->first();
                    if($uriageDB->seikyu_no != null) {
                        DB::table('t_seikyu')->where('seikyu_no', $uriageDB->seikyu_no)->update(['seikyu_hako_flg' => 0, 'upd_dt' => \Carbon\Carbon::now()]);
                    }
                    if($uriageDB->sime_kakutei_kbn == 0) {
                        $dataUriageUpdate = [
                            'souryo_kbn' => $uriage['souryo_kbn'] ?? null, 
                            'syaban' => $uriage['syaban'] ?? null, 
                            'jyomuin_cd' => $uriage['jyomuin_cd'] ?? null, 
                            'yousya_cd' => $uriage['yousya_cd'] ?? null, 
                            'biko' => $uriage['biko'] ?? null, 
                            'unchin_mikakutei_kbn' => $uriage['unchin_mikakutei_kbn'] ?? null, 
                            'unchin_kin' => $uriage['unchin_kin'] ?? null, 
                            'tyukei_kin' => $uriage['tyukei_kin'] ?? null, 
                            'tukoryo_kin' => $uriage['tukoryo_kin'] ?? null, 
                            'tesuryo_kin' => $uriage['tesuryo_kin'] ?? null, 
                            'nieki_kin' => $uriage['nieki_kin'] ?? null, 
                            'syuka_kin' => $uriage['syuka_kin'] ?? null, 
                            'menzei_kbn' => $uriage['menzei_kbn'] ?? null
                        ];

                        if($uriage['menzei_kbn'] == 0) {
                            $ninusiCd = $uriageDB->ninusi_cd;
                            $ninusi = DB::table('m_ninusi')->whereRaw(
                                    'ninusi_cd = (
                                        SELECT COALESCE(seikyu_cd, ninusi_cd) 
                                        FROM m_ninusi
                                        WHERE ninusi_cd = ?
                                    )', [$ninusiCd])->first();
                            if(!empty($ninusi)&& $ninusi->zei_keisan_kbn == 3) {
                                $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $ninusiCd)->first();
                                $seikyuKinTax = roundFromKbnTani(($uriage['unchin_kin'] + $uriage['tyukei_kin'] + $uriage['tesuryo_kin'] + $uriage['nieki_kin'] + $uriage['syuka_kin']) * configParam('TAX_RATE'), intval($ninusi->zei_hasu_kbn), intval($ninusi->zei_hasu_tani));
                                $dataUriageUpdate['seikyu_kin_tax'] = $seikyuKinTax;
                            } else {
                                $dataUriageUpdate['seikyu_kin_tax'] = null;
                            }
                            $yousyaCd = $uriageDB->yousya_cd;
                            $yousya = DB::table('m_yousya')->whereRaw(
                                    'yousya_cd = (
                                        SELECT COALESCE(siharai_cd, yousya_cd) 
                                        FROM m_yousya
                                        WHERE yousya_cd = ?
                                    )', [$yousyaCd])->first();
                            if(!empty($yousya) && $yousya->zei_keisan_kbn == 3) {
                                $yousya = DB::table('m_yousya')->where('yousya_cd', $yousyaCd)->first();
                                $yousyaKinTax = roundFromKbnTani(($uriage['yosya_tyukei_kin'] * configParam('TAX_RATE')), intval($yousya->zei_hasu_kbn), intval($yousya->zei_hasu_tani));
                                $dataUriageUpdate['yosya_kin_tax'] = $yousyaKinTax;
                            } else {
                                $dataUriageUpdate['yosya_kin_tax'] = null;
                            }
                        
                        }
                        $uriageDB->update($dataUriageUpdate);
                    }
                }
            }

            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => [
                    'nyusyuko_den_no' =>  $nyusyukoDenNo
                ],
                'message' => $flgInsert ? trans('messages.inserted') : trans('messages.updated2')
            ];
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Error updating data nyusyuko nyuryoku: ' . $e->getMessage());
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => trans('messages.E0012')
            ];
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $recordHead = TNyusyukoHead::where('nyusyuko_den_no', $id)->first();
            $meisais = TNyusyukoMeisai::where('nyusyuko_den_no', $id)->get();
            
            $ninusi = MNinusi::where('ninusi_cd', $recordHead->ninusi_cd)->first();
            $uriage = TUriage::where('uriage_den_no', $recordHead->uriage_den_no)->first();
            if(!empty($meisais)) {

                foreach ($meisais as $key => $recordMeisai) {
                    $sokoHinmei = DB::table('m_soko_hinmei')->where([
                        ['ninusi_cd', '=', $ninusi->ninusi_cd],
                        ['hinmei_cd', '=', $recordMeisai->hinmei_cd]
                    ])->first();

                    $zaikoDelete = TZaiko::where([
                        ['hinmei_cd', '=', $recordMeisai->hinmei_cd],
                        ['soko_cd', '=', $recordMeisai->soko_cd],
                        ['location', '=', $recordMeisai->location],
                        ['bumon_cd', '=', $recordHead->bumon_cd],
                        ['ninusi_cd', '=', $recordHead->ninusi_cd]
                    ]);
                    $this->__appendQueryLot($ninusi, $recordMeisai, $zaikoDelete);
                    $zaikoDelete = $zaikoDelete->first();
                    if(!empty($zaikoDelete)) {
                        $su = $recordHead->nyusyuko_kbn == 1 || $recordHead->nyusyuko_kbn == 4 || $recordHead->nyusyuko_kbn == 5? ($zaikoDelete->su - $recordMeisai->su) : ($zaikoDelete->su + $recordMeisai->su);
                        $irisu = !empty($sokoHinmei) ? ( !empty($sokoHinmei->irisu) ? $sokoHinmei->irisu : 1 ) : 1;
                        if($su >= 0) {
                            $caseSu = floor($su / $irisu);
                        } else {
                            $caseSu = ceil($su / $irisu);
                        }
                        $hasu =  $su % $irisu;
                        $zaikoDelete->update([
                            'su' => $su,
                            'hasu' => $hasu,
                            'case_su' => $caseSu
                        ]);
                    }
                    DB::table('t_zaiko_nyusyuko_meisai')->where('nyusyuko_den_no', $recordHead->nyusyuko_den_no)->where('nyusyuko_den_meisai_no', $recordMeisai->nyusyuko_den_meisai_no)->delete();
                }
            }

            if(!empty($uriage)) {
                if($uriage->seikyu_no != null) {
                    DB::table('t_seikyu')->where('seikyu_no', $uriage->seikyu_no)->update(['seikyu_hako_flg' => 0, 'upd_dt' => \Carbon\Carbon::now()]);
                }
            }
            TNyusyukoMeisai::where('nyusyuko_den_no', $id)->delete();
            TNyusyukoHead::where('nyusyuko_den_no', $id)->delete();
            if($recordHead->uriage_den_no) {
                TUriage::where('uriage_den_no', $recordHead->uriage_den_no)->delete();
            }

            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => null
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }

    private function __createTZaikoCaseKbn5($recordHead, $recordMeisai, $ninusi, $type, $irisu) {
        $nyusyukoDenNo = $recordHead->nyusyuko_den_no;
        $zaiko = $this->__updateTzaikoCaseKbn5($recordHead, $recordMeisai, $ninusi, $type, $irisu);
        $seq = DB::table('t_zaiko_nyusyuko_meisai')->max('seq_no');
        DB::table('t_zaiko_nyusyuko_meisai')->insert([
            'seq_no' => $seq + 1,
            'zaiko_seq_no' => $zaiko->seq_no,
            'nyusyuko_den_no' => $nyusyukoDenNo,
            'nyusyuko_den_meisai_no' => $recordMeisai->nyusyuko_den_meisai_no,
            'add_user_cd' => Auth::id(),
            'upd_user_cd' => Auth::id(),
            'add_dt' => \Carbon\Carbon::now(),
            'upd_dt' => \Carbon\Carbon::now(),
        ]);
    }

    private function __deleteTZaikoCaseKbn5($recordHead, $recordMeisai, $ninusi, $type, $irisu) {
        if(!empty($recordMeisai)) {
            $nyusyukoDenNo = $recordHead->nyusyuko_den_no;
            $dataZaiko = [
                'bumon_cd' => $recordHead->bumon_cd,
                'hinmei_cd' => $recordMeisai->hinmei_cd,
                'soko_cd' => $recordMeisai->soko_cd,
                'location' => $recordMeisai->location,
                'lot1' => $ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot1 : '',
                'lot2' => $ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot2 : '',
                'lot3' => $ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot3 : '',
                'case_su' => $recordMeisai->case_su,
                'hasu' => $recordMeisai->hasu,
                'su' => $recordMeisai->su
            ];
            $dataZaikoInsert = $dataZaiko;
            $dataZaikoInsert['seq_no'] = TZaiko::max('seq_no') + 1;

            $zaiko = TZaiko::where([
                ['hinmei_cd','=', $dataZaiko['hinmei_cd']],
                ['soko_cd','=', $dataZaiko['soko_cd']],
                ['location','=', $dataZaiko['location']],
                ['bumon_cd', '=', $recordHead->bumon_cd],
                ['ninusi_cd', '=', $recordHead->ninusi_cd]
            ]);

            $this->__appendQueryLot($ninusi, $recordMeisai, $zaiko);
            $zaiko = $zaiko->first();
            if(empty($zaiko)) {
                $dataZaikoInsert['su'] = -$dataZaikoInsert['su'];
                $dataZaikoInsert['bumon_cd'] = $recordHead->bumon_cd;
                $dataZaikoInsert['ninusi_cd'] = $recordHead->ninusi_cd;
                $zaiko = TZaiko::create($dataZaikoInsert);
                $su = $zaiko->su;
            } else {
                $su = $zaiko->su - $dataZaiko['su'];
            }


            $irisu = !empty($irisu) ? $irisu : 1;

            if ($su >= 0) {
                $caseSu = floor($su / $irisu);
            } elseif ($su < 0) {
                $caseSu = ceil($su / $irisu);
            }

            $hasu =  $su % $irisu;
            $zaiko->update([
                'su' => $su,
                'case_su' => $caseSu,
                'hasu' => $hasu
            ]);
        }
    }

    private function __updateTzaikoCaseKbn5($recordHead, $recordMeisai, $ninusi, $type, $irisu) {
        $nyusyukoDenNo = $recordHead->nyusyuko_den_no;
        $dataZaiko = [
            'bumon_cd' => $recordHead->bumon_cd,
            'hinmei_cd' => $recordMeisai->hinmei_cd,
            'soko_cd' => $recordMeisai->soko_cd,
            'location' => $recordMeisai->location,
            'lot1' => $ninusi->lot_kanri_kbn >= 1 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot1 : '',
            'lot2' => $ninusi->lot_kanri_kbn >= 2 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot2 : '',
            'lot3' => $ninusi->lot_kanri_kbn >= 3 || $ninusi->lot_kanri_kbn == null ? $recordMeisai->lot3 : '',
            'case_su' => $recordMeisai->case_su,
            'hasu' => $recordMeisai->hasu,
            'su' => $recordMeisai->su
        ];

        $dataZaikoInsert = $dataZaiko;
        $dataZaikoInsert['seq_no'] = TZaiko::max('seq_no') + 1;

        $zaiko = TZaiko::where([
            ['hinmei_cd','=', $dataZaiko['hinmei_cd']],
            ['soko_cd','=', $dataZaiko['soko_cd']],
            ['location','=', $dataZaiko['location']],
            ['bumon_cd', '=', $recordHead->bumon_cd],
            ['ninusi_cd', '=', $recordHead->ninusi_cd]
        ]);

        $this->__appendQueryLot($ninusi, $recordMeisai, $zaiko);
        $zaiko = $zaiko->first();

        if(empty($zaiko)) {
            $dataZaikoInsert['su'] = $dataZaikoInsert['su'];
            $dataZaikoInsert['bumon_cd'] = $recordHead->bumon_cd;
            $dataZaikoInsert['ninusi_cd'] = $recordHead->ninusi_cd;
            $zaiko = TZaiko::create($dataZaikoInsert);
            $su = $zaiko->su;
        } else {
            $su = $zaiko->su + $dataZaiko['su'];
        }

        $irisu = !empty($irisu) ? $irisu : 1;
        if ($su >= 0) {
            $caseSu = floor($su / $irisu);
        } elseif ($su < 0) {
            $caseSu = ceil($su / $irisu);
        }
        $hasu =  $su % $irisu;
        $zaiko->update([
            'su' => $su,
            'case_su' => $caseSu,
            'hasu' => $hasu
        ]);
        return $zaiko;
    }

    private function __appendQueryLot($ninusi, $recordMeisai, &$query) {
        if(!empty($ninusi->lot_kanri_kbn)) 
        {
            if($ninusi->lot_kanri_kbn >= 1) {
                $query->where('lot1', $recordMeisai->lot1);
            }
            if($ninusi->lot_kanri_kbn >= 2) {
                $query->where('lot2', $recordMeisai->lot2);
            }

            if($ninusi->lot_kanri_kbn >= 3) {
                $query->where('lot3', $recordMeisai->lot3);
            }
        }
    }
}
