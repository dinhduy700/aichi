<?php

namespace App\Http\Controllers\Nyukin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\NyukinRepository;
use Illuminate\Http\Request;
use App\Http\Requests\NyukinRequest;
use App\Http\Requests\Nyukin\NyukinSearchRequest;
use Illuminate\Support\Facades\DB;

class NyukinController extends Controller
{
    protected $nyukinRepository;

    public function __construct(
        NyukinRepository $nyukinRepository
    ) {
        $this->nyukinRepository = $nyukinRepository;
    }

    /**
     * Display the Meisyo index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/t_nyukin.php'));
        return view('nyukin.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Display the form for creating a new Meisyo.
     *
     * @param  \App\Http\Requests\MeisyoRequest  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $maxNyukinNo = DB::table('t_nyukin')->max('nyukin_no');
        return view('nyukin.form', ['request' => $request, 'maxNyukinNo' => $maxNyukinNo + 1]);
    }

    /**
     * Store a newly created Meisyo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NyukinRequest $request)
    {
        $response = $this->nyukinRepository->store($request);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Display the specified Meisyo.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {

    }

    /**
     * Display the form for editing the specified Meisyo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $meisyoKbn
     * @param  int  $meisyoCd
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $nyukinCd)
    {
        $nyukin = $this->nyukinRepository->getDetail(urldecode($nyukinCd));
        if(empty($nyukin)) {
            abort(404);
        }
        $ninusiCd = $nyukin->ninusi_cd;

        $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $ninusiCd)->first();
        $seikyuSel = DB::table('t_seikyu')
                    ->select('t_seikyu.seikyu_no', 'seikyu_sime_dt', DB::raw('(t_seikyu.konkai_torihiki_kin - COALESCE(t_seikyu_nyukin.nyukin_kin,0)) AS konkai_torihiki_kin'))
                    ->leftJoin(DB::raw('(SELECT seikyu_no, SUM(nyukin_kin) AS nyukin_kin FROM t_seikyu_nyukin GROUP BY seikyu_no) AS t_seikyu_nyukin'), 't_seikyu_nyukin.seikyu_no', '=', 't_seikyu.seikyu_no')
                    ->where(function($query) use ($ninusi, $ninusiCd) {
                      if(!empty($ninusi) && $ninusi->seikyu_cd) {
                        $query->where('t_seikyu.ninusi_cd', '=', $ninusi->seikyu_cd);
                      } else {
                        $query->where('t_seikyu.ninusi_cd', '=', $ninusiCd);
                      }
                    })
                    ->where(function($where) {
                        $where->whereNull('t_seikyu_nyukin.seikyu_no')
                              ->orWhere('t_seikyu_nyukin.nyukin_kin', '<', DB::raw('t_seikyu.konkai_torihiki_kin'));
                    })
                    ->orderBy('seikyu_sime_dt')
                    ->orderBy('konkai_torihiki_kin')
                    ->get();

        $hikiSeikyu = DB::table('t_seikyu')
                        ->select('seikyu_sime_dt', 't_seikyu_nyukin.nyukin_kin as konkai_torihiki_kin', 't_seikyu.seikyu_no')
                        ->join('t_seikyu_nyukin', 't_seikyu_nyukin.seikyu_no', 't_seikyu.seikyu_no')
                        ->where('t_seikyu_nyukin.nyukin_no', '=', $nyukin->nyukin_no)
                        ->where('t_seikyu.ninusi_cd', $ninusiCd)
                        ->orderBy('seikyu_sime_dt')
                        ->orderBy('konkai_torihiki_kin')
                        ->get();
        return view('nyukin.form', ['nyukin' => $nyukin, 'request' => $request, 'seikyuSel' => $seikyuSel, 'hikiSeikyu' => $hikiSeikyu]);
    }

    /**
     * Update the specified Meisyo in storage.
     *
     * @param  \App\Http\Requests\MeisyoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NyukinRequest $request, $nyukinCd)
    {
        $response = $this->nyukinRepository->update($request, $nyukinCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified Meisyo from storage.
     *
     * @param  int  $meisyoKbn
     * @param  int  $meisyoCd
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($nyukinCd)
    {
        $response = $this->nyukinRepository->delete($nyukinCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }

    /**
     * Get a list with total count of Meisyo data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->nyukinRepository->getListWithTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json($data);
    }

    public function autoFillSeikyuSimeDt(Request $request) 
    {
        $seikyuSimeDt = '';
        if($request->ninusi_cd && $request->nyukin_dt) {
            $date = \Carbon\Carbon::createFromFormat('Y/m/d', $request->nyukin_dt);
            $day = $date->day;

            $addMonth = false;
            $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $request->ninusi_cd ?? '')->first();
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
                    $seikyuSimeDt = '';
                } else {
                    if($simebi > $lastDayOfMonth) {
                        $simebi = $lastDayOfMonth;
                    }
                    $seikyuSimeDt = $year . '/' . str_pad($month, 2, "0", STR_PAD_LEFT) . '/' . str_pad($simebi, 2, "0", STR_PAD_LEFT);
                }
            }

        }
        return response()->json([
            'seikyu_sime_dt' => $seikyuSimeDt
        ]);
    }

    public function getListNyukinSeiKyu(Request $request) 
    {
        $seikyuSel = null;
        if($request->filled('ninusi_cd')) {
           $seikyuSel = DB::table('t_seikyu')
                        ->select('t_seikyu.seikyu_no', 'seikyu_sime_dt', DB::raw('(t_seikyu.konkai_torihiki_kin - COALESCE(t_seikyu_nyukin.nyukin_kin,0)) AS konkai_torihiki_kin'))
                        ->leftJoin(DB::raw('(SELECT seikyu_no, SUM(nyukin_kin) AS nyukin_kin FROM t_seikyu_nyukin GROUP BY seikyu_no) AS t_seikyu_nyukin'), 't_seikyu_nyukin.seikyu_no', '=', 't_seikyu.seikyu_no')
                        ->where('t_seikyu.ninusi_cd', '=', $request->ninusi_cd)
                        ->where(function($where) {
                            $where->whereNull('t_seikyu_nyukin.seikyu_no')
                                  ->orWhere('t_seikyu_nyukin.nyukin_kin', '<', DB::raw('t_seikyu.konkai_torihiki_kin'));
                        })
                        ->orderBy('seikyu_sime_dt')
                        ->orderBy('konkai_torihiki_kin')
                        ->get(); 
        }
        return response()->json([
            'data' => $seikyuSel
        ]);
    }

    public function validateFormSearch(NyukinSearchRequest $request) {
      return response()->json([]);
    }
}
