<?php 
namespace App\Http\Repositories;

use App\Models\TNyukin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NyukinRepository 
{
    public function qbExport($request, $route = null, &$tNyukin = null)
    {
        $tNyukin = new TNyukin();
        $table = $tNyukin->getTable();
        $qb = $tNyukin->select("{$table}.*");
        $qb->joinMNinusi()->addSelect('ninusi1_nm');
        $qb->filter($request);
        $qb->orderBy('nyukin_no');

        return $qb;
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $tNyukin = null;
        $qb = $this->qbExport($cloneReq, $routeNm, $tNyukin);

        return $qb;

    }

    public function getListWithTotalCount($request)
    {
        $list = TNyukin::select('t_nyukin.*', DB::raw('m_ninusi.ninusi_ryaku_nm as ninusi_nm'))->filterList($request);
        $list = $list->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_nyukin.ninusi_cd');
        if($request->filled('ninusi_cd')) {
            $list = $list->where('t_nyukin.ninusi_cd', 'ilike', makeEscapeStr($request->ninusi_cd). '%');
        }
        if($request->filled('ninusi_nm')) {
            $list = $list->where(function ($query) use ($request) {
                $query->where('m_ninusi.ninusi_ryaku_nm', 'ilike', makeEscapeStr($request->ninusi_nm). '%');
                $query->orWhere('m_ninusi.kana', 'ilike', makeEscapeStr($request->ninusi_nm). '%');
            });
        }

        if($request->filled('hed_nyukin_dt_from')) {
            $list = $list->where('nyukin_dt', '>=', $request->hed_nyukin_dt_from);
        }

        if($request->filled('hed_nyukin_dt_to')) {
            $list = $list->where('nyukin_dt', '<=', $request->hed_nyukin_dt_to);
        }

        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function getDetail($nyukinCd) {
        $tNyukin = TNyukin::select('t_nyukin.*', DB::raw('m_ninusi.ninusi_ryaku_nm as ninusi_nm'))->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_nyukin.ninusi_cd')->where([
            ['nyukin_no', '=', $nyukinCd],
        ])->first();
        return $tNyukin;
    }

    public function store($request) {
        DB::beginTransaction();
        try {
            $maxValue = TNyukin::max('nyukin_no');
            $request->merge(['nyukin_no' => $maxValue + 1]);
            $nyukin = TNyukin::create($request->all()); 
            $this->calculatorNyukinSeikyu($request, $nyukin->nyukin_no);
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $nyukin
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

    public function update($request, $nyukinCd) {
        DB::beginTransaction();
        try {
            $nyukin = TNyukin::where([
                ['nyukin_no', '=', $nyukinCd],
            ])->first();

            if ($nyukin) {
                $nyukin->update($request->all());
                $this->calculatorNyukinSeikyu($request, $nyukinCd);
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

    public function delete($nyukinCd) {
        DB::beginTransaction();
        try {
            TNyukin::where([
                ['nyukin_no', '=', $nyukinCd],
            ])->delete();
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

    private function calculatorNyukinSeikyu($request, $nyukinCd) {
        $listSeiku = [];
        $listSeikuNo = $request->seikyu_no_bot;
        if(!empty($listSeikuNo)) {
            foreach($listSeikuNo as $key => $value) {
                if(!empty($listSeiku[$request->seikyu_no_bot[$key].$request->seikyu_sime_dt_bot[$key]])) {
                    $listSeiku[$request->seikyu_no_bot[$key].$request->seikyu_sime_dt_bot[$key]]['konkai_torihiki_kin'] += $request->konkai_torihiki_kin_bot[$key];
                } else {
                    $listSeiku[$request->seikyu_no_bot[$key].$request->seikyu_sime_dt_bot[$key]] = [
                        'seikyu_no' => $request->seikyu_no_bot[$key],
                        'seikyu_sime_dt' => $request->seikyu_sime_dt_bot[$key],
                        'konkai_torihiki_kin' => $request->konkai_torihiki_kin_bot[$key]
                    ];
                }
            }
        }
        $total = ($request->genkin_kin ?? 0 ) +  ($request->furikomi_kin ?? 0 ) + ($request->furikomi_tesuryo_kin ?? 0 ) + ($request->tegata_kin ?? 0 ) + ($request->sousai_kin ?? 0 ) + ($request->nebiki_kin ?? 0 ) + ($request->sonota_nyu_kin ?? 0 );
        usort($listSeiku, array($this, 'compare'));
        DB::table('t_seikyu_nyukin')->where('nyukin_no', $nyukinCd)->delete();
        if(!empty($listSeiku)) {
            foreach ($listSeiku as $key => $value) {
                $seqNo = DB::table('t_seikyu_nyukin')->max('seq_no');
                DB::table('t_seikyu_nyukin')->insert([
                    'seq_no' => $seqNo + 1,
                    'seikyu_no' => $value['seikyu_no'],
                    'nyukin_no' => $nyukinCd,
                    'nyukin_kin' => $total > $value['konkai_torihiki_kin'] ? $value['konkai_torihiki_kin'] : $total,
                    'upd_user_cd' => Auth::id(),
                    'add_user_cd' => Auth::id(),
                    'add_dt' => \Carbon\Carbon::now(),
                    'upd_dt' => \Carbon\Carbon::now(),
                ]);
                $total = $total - ($total > $value['konkai_torihiki_kin'] ? $value['konkai_torihiki_kin'] : $total);
                if($total <= 0) {
                    break;
                }
            }
        }
    }

    private function compare($a, $b) {
        $dateComparison = strtotime($a['seikyu_sime_dt']) - strtotime($b['seikyu_sime_dt']);
        return ($dateComparison == 0) ? ($a['konkai_torihiki_kin'] - $b['konkai_torihiki_kin']) : $dateComparison;
    }
}
