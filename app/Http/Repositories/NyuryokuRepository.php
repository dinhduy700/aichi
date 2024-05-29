<?php 
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use App\Models\TNyusyukoMeisai;
use App\Models\TNyusyukoHead;
use DB;

class NyuryokuRepository 
{
    public function getListWithTotalCount($request) 
    {
        $qb = DB::table('t_nyusyuko_head as nyuh')
            ->select(
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


                'm_soko_hinmei.kikaku',
                'm_soko_hinmei.irisu',

                'm_soko_hinmei.hinmei_nm',

                // 'tani.tani_cd',
                DB::raw('tani.meisyo_nm as tani_nm'),
                'soko.soko_nm'


            )
            ->join('t_nyusyuko_meisai as nyum', 'nyum.nyusyuko_den_no', 'nyuh.nyusyuko_den_no')
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
            
            ->orderBy('nyuh.nyusyuko_den_no');
            
        if($request->filled('hed_nyusyuko_den_no'))
        {
            $qb->where('nyuh.nyusyuko_den_no', $request->hed_nyusyuko_den_no);
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
                $qb->where('m_hinmei.hinmei_nm', 'ilike', '%'.makeEscapeStr($request->hinmei_nm).'%');
            } else {
                $qb->where('m_hinmei.hinmei_nm', 'ilike','%'.makeEscapeStr($request->hinmei_nm));
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
        DB::beginTransaction();
        try {
            $nyusyukoHead = $request->nyusyuko_head;
            $nyusyukoMeisai = $request->nyusyuko_meisai;
            $uriage = $request->uriage;
            $nyusyukoDenNo = null;
            $flgInsert = false;
            if(!empty($nyusyukoHead)) {
                if(!empty($nyusyukoHead['nyusyuko_den_no'])) {
                    $nyusyukoDenNo = $nyusyukoHead['nyusyuko_den_no'] ?? 0;
                    $recordHead = TNyusyukoHead::find($nyusyukoDenNo);
                    if(!empty($recordHead)) {
                        $recordHead->update($nyusyukoHead);
                    }
                } else {
                    $totalMax = TNyusyukoHead::max('nyusyuko_den_no');
                    $nyusyukoHead['nyusyuko_den_no'] = $totalMax + 1;
                    $recordHead = TNyusyukoHead::create($nyusyukoHead);
                    $flgInsert = true;
                    $nyusyukoDenNo = $recordHead->nyusyuko_den_no;
                }
            }

            if(!empty($nyusyukoMeisai) && !empty($nyusyukoDenNo)) {
                foreach ($nyusyukoMeisai as $key => $meisai) {
                    if(!empty($meisai['nyusyuko_den_meisai_no'])) {
                        // Update nyusyuko_meisai
                        $recordMeisai = TNyusyukoMeisai::where([
                            ['nyusyuko_den_meisai_no', '=', $meisai['nyusyuko_den_meisai_no']],
                            ['nyusyuko_den_no', '=', $meisai['nyusyuko_den_no']],
                        ])->first();

                        if(!empty($recordMeisai)) {
                            if(!empty($meisai['del_flg'])) {
                                $recordMeisai->delete();
                            } else {
                                $recordMeisai->update($meisai);
                                \Log::info($recordMeisai);
                            }
                        }

                    } else {
                        $meisai['nyusyuko_den_no'] = $nyusyukoDenNo;
                        $totalMax = TNyusyukoMeisai::where([
                            ['nyusyuko_den_no', '=', $meisai['nyusyuko_den_no']],
                        ])->max('nyusyuko_den_meisai_no');
                        $meisai['nyusyuko_den_meisai_no'] = $totalMax + 1;
                        $recordMeisai =  TNyusyukoMeisai::create($meisai);

                    }
                }
            }

            if(!empty($uriage) && !empty($nyusyukoDenNo)) {
                if(!empty($uriage['uriage_den_no'])) {
                    DB::table('t_uriage')->where('uriage_den_no', $uriage['uriage_den_no'])
                    ->update([
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
                        'menzei_kbn' => $uriage['menzei_kbn'] ?? null, 
                    ]);
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
            \Log::error('Error updating data inputs-entry: ' . $e->getMessage());
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => trans('messages.E0012')
            ];
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            TNyusyukoHead::where('nyusyuko_den_no', $id)->delete();
            TNyusyukoMeisai::where('nyusyuko_den_no', $id)->delete();
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
}
