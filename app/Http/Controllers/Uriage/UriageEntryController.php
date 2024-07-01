<?php

namespace App\Http\Controllers\Uriage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\UriageRepository;
use App\Http\Requests\UriageRequest;
use App\Http\Requests\UriageFormSearchRequest;
use Illuminate\Http\Response;
use DB;

class UriageEntryController extends Controller
{
    const URIAGE_PG_NM = 'uriage';
    const URIAGE_FUNCTON_INIT_COPY = 'init_popup_copy';
    const URIAGE_FUNCTON_INIT_SEARCH = 'init_popup_search';
    const URIAGE_FUNCTON_INIT_COLUMN = 'init_popup_column';

    protected $uriageRepository;

    public function __construct(
        UriageRepository $uriageRepository
    ) {
        $this->uriageRepository = $uriageRepository;
    }

    /**
     * Display the Uriage index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/t_uriage.php'));
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_COPY)
                ->first();
        if(!empty($init)) {
            $dataInit = $this->__getInitCopy($init);
        } else {
            $dataInit = $this->__getDefaultInitCopy();
        }
        $dataInitSearchPopup = $this->__getInitSearch();
        $dataInitColumnPopup = $this->__getInitColumn();
        $dataGenkin = DB::table('m_meisyo')->select('*', DB::raw('meisyo_cd as genkin_cd'), DB::raw('meisyo_nm as genkin_nm'))
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_GENKIN'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->get();
        $dataUnchinMikakutei = DB::table('m_meisyo')->select('*', DB::raw('meisyo_cd as unchin_mikakutei_kbn'), DB::raw('meisyo_nm as unchin_mikakutei_nm'))
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->get();
        return view('uriage.uriage-entry.index', ['setting' => $setting, 'request' => $request, 'dataInit' => $dataInit, 'dataInitSearchPopup' => $dataInitSearchPopup, 'dataInitColumnPopup' => $dataInitColumnPopup, 'dataGenkin' => $dataGenkin, 'dataUnchinMikakutei' => $dataUnchinMikakutei]);
    }




    /**
     * Get a list with total count of Uriage data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request) 
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');
        $keyInitSearch = $this->__getKeyInitSearch();
        $listData = $this->uriageRepository->getListWithTotalCount($request, $keyInitSearch);
        
        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        
        return response()->json($data);
    }

    /**
     * Handle the export of data to Excel format.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcelDataTable()
    {

    }


    public function valdateRow(UriageRequest $request) {
        return response()->json([
            'status' => 200,
            'data' => $request->all()
        ]);
    }

    public function searchSuggestion(Request $request) {
        $field = $request->field;
        $value = makeEscapeStr($request->value).'%';
        $data = [];

        switch ($field) {
            case 'bumon_cd':
                $data = \DB::table('m_bumon')
                    ->select('*')
                    ->where('bumon_cd', 'ILIKE', $value)
                    ->orderBy('bumon_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'bumon_nm':
                $data = \DB::table('m_bumon')
                    ->select('*')
                    ->where(function ($query) use ($value) {
                        $query->where('bumon_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('bumon_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hatuti_cd':
                $data = \DB::table('m_hachaku')
                    ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'), \DB::raw('hachaku_nm as hatuti_hachaku_nm'))
                    ->where('hachaku_cd', 'ILIKE', $value)
                    ->orderBy('hachaku_cd', 'ASC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hatuti_nm':
                $data = \DB::table('m_hachaku')
                    ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'), \DB::raw('hachaku_nm as hatuti_hachaku_nm'))
                    ->where(function ($query) use ($value) {
                        $query->where('hachaku_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('hachaku_cd', 'ASC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'genkin_cd':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as genkin_nm'), \DB::raw('meisyo_cd as genkin_cd'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_GENKIN'))
                    ->orderBy('genkin_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'genkin_nm':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as genkin_nm'), \DB::raw('meisyo_cd as genkin_cd'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_GENKIN'))
                    ->orderBy('genkin_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'ninusi_cd':
                $data = \DB::table('m_ninusi')
                    ->select('*', \DB::raw('ninusi_ryaku_nm as ninusi_nm'))
                    ->where('ninusi_cd', 'ILIKE', $value)
                    ->orderBy('ninusi_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'ninusi_nm':
                $data = \DB::table('m_ninusi')
                    ->select('*', \DB::raw('ninusi_ryaku_nm as ninusi_nm'))
                    ->where(function ($query) use ($value) {
                        $query->where('ninusi_ryaku_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('ninusi_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hachaku_cd':
                $data = \DB::table('m_hachaku')
                    ->select('*')
                    ->where('hachaku_cd', 'ILIKE', $value)
                    ->orderBy('hachaku_cd', 'ASC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hachaku_nm':
                $data = \DB::table('m_hachaku')
                    ->select('*')
                    ->where(function ($query) use ($value) {
                        $query->where('hachaku_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('hachaku_cd', 'ASC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'syubetu_cd':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as syubetu_nm'), \DB::raw('meisyo_cd as syubetu_cd'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_SYUBETU'))
                    ->orderBy('syubetu_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'syubetu_nm':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as syubetu_nm'), \DB::raw('meisyo_cd as syubetu_cd'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_SYUBETU'))
                    ->orderBy('syubetu_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'hinmei_cd':
                $qb = \DB::table('m_hinmei')
                    ->select('m_hinmei.*', \DB::raw('m_hinmoku.hinmoku_nm'))
                    ->leftJoin('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd')
                    ->where('m_hinmei.hinmei_cd', 'ILIKE', $value);
                if(!empty($request->list_check)) {
                    if($request->filled('list_check.ninusi_cd') && $request->list_check['ninusi_cd']) {
                        $qb = $qb->where('m_hinmei.ninusi_id', $request->list_check['ninusi_cd']);
                    }
                }
                $qb = $qb->orderBy('hinmei_cd', 'DESC');
                $data = $qb->limit(configParam('LIMIT_SUGGEST'))->get();
                break;
            case 'hinmei_nm':
                $qb = \DB::table('m_hinmei')
                    ->select('m_hinmei.*', \DB::raw('m_hinmoku.hinmoku_nm'))
                    ->join('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd')
                    ->where(function ($query) use ($value) {
                        $query->orWhere('m_hinmei.hinmei_nm', 'ILIKE', $value)
                            ->orWhere('m_hinmei.kana', 'ILIKE', $value);
                    });
                if(!empty($request->list_check)) {
                    if($request->filled('list_check.ninusi_cd') && $request->list_check['ninusi_cd']) {
                        $qb = $qb->where('m_hinmei.ninusi_id', $request->list_check['ninusi_cd']);
                    }
                }
                $qb = $qb->orderBy('hinmei_cd', 'DESC');    
                $data = $qb->limit(configParam('LIMIT_SUGGEST'))->get();
                break;
            case 'hinmoku_nm':
                $data = \DB::table('m_hinmoku')
                    ->select('*', \DB::raw('null as hinmei_mm'), \DB::raw('null as hinmei_cd'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('hinmoku_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'tani_cd':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as tani_nm'), \DB::raw('meisyo_cd as tani_cd'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                    ->orderBy('meisyo_cd', 'ASC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'tani_nm':
                $data = \DB::table('m_meisyo')
                   ->select('*', \DB::raw('meisyo_nm as tani_nm'), \DB::raw('meisyo_cd as tani_cd'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                    ->orderBy('meisyo_cd', 'ASC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'gyosya_cd':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as gyosya_nm'), \DB::raw('meisyo_cd as gyosya_cd'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'gyosya_nm':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as gyosya_nm'), \DB::raw('meisyo_cd as gyosya_cd'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'biko_cd':
                $data = \DB::table('m_biko')
                    ->select('*', \DB::raw('biko_nm as biko'))
                    ->where('biko_cd', 'ILIKE', $value)
                    ->orderBy('biko_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'biko':
                $data = \DB::table('m_biko')
                    ->select('*', \DB::raw('biko_nm as biko'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('biko_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('biko_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'jyomuin_cd':
                $data = \DB::table('m_jyomuin')
                    ->select('*')
                    ->where('jyomuin_cd', 'ILIKE', $value)
                    ->orderBy('jyomuin_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'jyomuin_nm':
                $data = \DB::table('m_jyomuin')
                    ->select('*')
                    ->where(function ($query) use ($value) {
                        $query->orWhere('jyomuin_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('jyomuin_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'add_tanto_cd':
                $data = \DB::table('m_jyomuin')
                    ->select('*', \DB::raw('jyomuin_cd as add_tanto_cd'), \DB::raw('jyomuin_nm as add_tanto_nm'))
                    ->where('jyomuin_cd', 'ILIKE', $value)
                    ->orderBy('jyomuin_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'add_tanto_nm':
                $data = \DB::table('m_jyomuin')
                    ->select('*', \DB::raw('jyomuin_cd as add_tanto_cd'), \DB::raw('jyomuin_nm as add_tanto_nm'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('jyomuin_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('jyomuin_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'yousya_cd':
                $data = \DB::table('m_yousya')
                    ->select('*', \DB::raw('yousya_ryaku_nm as yousya_nm'))
                    ->where('yousya_cd', 'ILIKE', $value)
                    ->orderBy('yousya_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'yousya_nm':
                $data = \DB::table('m_yousya')
                    ->select('*', \DB::raw('yousya_ryaku_nm as yousya_nm'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('yousya_ryaku_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->orderBy('yousya_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'unchin_mikakutei_kbn':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as unchin_mikakutei_nm'), \DB::raw('meisyo_cd as unchin_mikakutei_kbn'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'unchin_mikakutei_nm':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as unchin_mikakutei_nm'), \DB::raw('meisyo_cd as unchin_mikakutei_kbn'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'tanka_kbn':
                $data = DB::table('m_meisyo')
                    ->select('*', DB::raw('meisyo_cd as tanka_kbn'), DB::raw('meisyo_nm as tanka_nm'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_TANKA'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'tanka_nm':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as tanka_nm'), \DB::raw('meisyo_cd as tanka_kbn'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_TANKA'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'jyutyu_kbn':
                $data = DB::table('m_meisyo')
                    ->select('*', DB::raw('meisyo_cd as jyutyu_kbn'), DB::raw('meisyo_nm as jyutyu_nm'))
                    ->where('meisyo_cd', 'ILIKE', $value)
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            case 'jyutyu_nm':
                $data = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as jyutyu_nm'), \DB::raw('meisyo_cd as jyutyu_kbn'))
                    ->where(function ($query) use ($value) {
                        $query->orWhere('meisyo_nm', 'ILIKE', $value)
                            ->orWhere('kana', 'ILIKE', $value);
                    })
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;

            case 'syaban': 
                $data = \DB::table('m_syaryo')
                    ->select( \DB::raw('m_syaryo.syaryo_cd as syaban'), 'm_jyomuin.jyomuin_cd', 'm_jyomuin.jyomuin_nm')
                    ->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 'm_syaryo.jyomuin_cd')
                    ->where('syaryo_cd', 'ILIKE', $value)
                    ->orderBy('syaryo_cd', 'DESC')
                    ->limit(configParam('LIMIT_SUGGEST'))
                    ->get();
                break;
            default:
                # code...
                break;
        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function getOtherColumn(Request $request) {
        $data = [];
        if($request->field_from && $request->field_to) {
            $arrField = !is_array($request->field_to) ? [$request->field_to] : $request->field_to;
            foreach ($arrField as $key => $field) {
                switch ($request->field_from) {
                    case 'ninusi_cd':
                        switch ($field) {
                            case 'hachaku_cd':
                                $data[$field] = \DB::table('m_hachaku')
                                        ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'))
                                        ->where('ninusi_id', $request->value_from)
                                        ->orderBy('hachaku_cd', 'DESC')
                                        ->get();
                                break;
                            case 'haitatu_tel': 
                                $data[$field]  = \DB::table('m_ninusi')
                                                ->select('m_ninusi.tel')
                                                ->Where('m_ninusi.ninusi_cd', $request->value_from)
                                                ->get();
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'hinmei_cd': 
                        switch ($field) {
                            case 'tani_cd':
                                $data[$field] = \DB::table('m_hinmei')
                                            ->select('m_hinmei.tani_cd', \DB::raw('m_meisyo_tani.meisyo_nm as tani_nm'))
                                            ->leftJoin('m_meisyo as m_meisyo_tani', function($query) {
                                                $query->on('m_meisyo_tani.meisyo_cd', '=', 'm_hinmei.tani_cd');
                                                $query->where('m_meisyo_tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
                                            })
                                            ->where('m_hinmei.hinmei_cd', $request->value_from)
                                            ->get();
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    default:
                        # code...
                        break;
                }
            }

            
        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function updateDataTable(Request $request) 
    {   
        $result = $this->uriageRepository->updateDataTable($request);
        return response()->json($result);
    }

    public function updateInitCopy(Request $request) 
    {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_COPY)
                ->first();
        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data['choice'.$i.'_nm'] = null;
            $data['choice'.$i.'_bool'] = null;
        }
        $inputs = $request->all();
        $i = 1;
        foreach ($inputs as $key => $value) {
            $data['choice'.$i.'_nm'] = $key;
            $data['choice'.$i.'_bool'] = $value == 'true' ? true : false;
            $i++;
        }
        if(!empty($init)) {
            DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_COPY)
                ->update($data);
        } else {
            $data['user_cd'] = \Auth::id();
            $data['pg_nm'] = self::URIAGE_PG_NM;
            $data['function'] = self ::URIAGE_FUNCTON_INIT_COPY;
            DB::table('m_user_pg_function')
                ->insert($data);
        }
        $dataInit = $this->__getInitCopy($data);
        return response()->json([
            'status' => 200,
            'data' => $dataInit
        ]);
    }

    public function updateInitSearch(UriageFormSearchRequest $request) {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_SEARCH)
                ->first();
        $all  = $request->all();


        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data['choice'.$i.'_nm'] = null;
            $data['choice'.$i.'_bool'] = null;
            $data['choice'.$i.'_dt'] = null;
            $data['choice'.$i.'_char'] = null;
            $data['choice'.$i.'_num'] = null;
        }

        $data['choice1_nm'] = 'hed_bumon_cd';
        $data['choice1_char'] =  $all['hed_bumon_cd'];
        $data['choice2_nm'] = 'hed_jyomuin_cd';
        $data['choice2_char'] =  $all['hed_jyomuin_cd'];

        unset($all['hed_bumon_cd']);
        unset($all['hed_jyomuin_cd']);

        if(!empty($all['chk'])) {
            $chk = $all['chk'];
            unset($all['chk']);
        }
        $inputs = collect($all)->filter(function ($value, $key) {
            return strpos($key, '_nm') === false;
        })->all();

        $choiceKeys = $this->__getKeyInitSearch();
        foreach ($inputs as $key => $value) {
            
            if(empty($choiceKeys[$key])) {
                continue;
            }
            $index = $choiceKeys[$key]['index'];
            $data['choice'.$index.'_nm'] = $key;
            if (strpos($key, '_dt') !== false) {
                $data['choice'.$index.'_dt'] = $value;
            } else {
                $data['choice'.$index.'_char'] = is_array($value) ? ( !empty($value) ? json_encode($value) : null) : $value;
            }
        }

        if(!empty($init)) {
            DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_SEARCH)
                ->update($data);
        } else {
            $data['user_cd'] = \Auth::id();
            $data['pg_nm'] = self::URIAGE_PG_NM;
            $data['function'] = self ::URIAGE_FUNCTON_INIT_SEARCH;
            DB::table('m_user_pg_function')
                ->insert($data);
        }

        return response()->json(['status' => 200, 'data'=> $this->__getInitSearch()]);       
    }

    public function updateInitColumn(Request $request) {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_COLUMN)
                ->first();
        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data['choice'.$i.'_nm'] = null;
            $data['choice'.$i.'_bool'] = null;
        }
        $inputs = $request->all();
        $i = 1;
        foreach ($inputs as $key => $value) {
            $data['choice'.$i.'_nm'] = $key;
            $data['choice'.$i.'_bool'] = $value == '1' ? true : false;
            $i++;
        }
        DB::beginTransaction();
        try {
            if(!empty($init)) {
                DB::table('m_user_pg_function')
                    ->where('user_cd', \Auth::id())
                    ->where('pg_nm', self::URIAGE_PG_NM)
                    ->where('function', self::URIAGE_FUNCTON_INIT_COLUMN)
                    ->update($data);
            } else {
                $data['user_cd'] = \Auth::id();
                $data['pg_nm'] = self::URIAGE_PG_NM;
                $data['function'] = self ::URIAGE_FUNCTON_INIT_COLUMN;
                DB::table('m_user_pg_function')
                    ->insert($data);
            }
            $dataInit = $this->__getInitColumn();
            DB::commit();
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => $dataInit
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => trans('messages.E0012')
            ]);
        }
    }

    public function valdateFormSearchUriage(UriageFormSearchRequest $request)
    {   
        $this->updateInitSearch($request);
        return response()->json([
            'status' => Response::HTTP_OK,
        ]);
    }

    public function calculatorRoundKintax(Request $request) {
        $data = null;
        if($request->menzei_kbn == 1) {
            $data = null;
        } else {
            $menzeiKbn = $request->menzei_kbn ?? 0;
            if($menzeiKbn == 0) {
                // if($request->filled('ninusi_cd')) {
                //     $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $request->ninusi_cd)->first();
                //     if(!empty($ninusi)) {
                //         if($ninusi->zei_keisan_kbn == 3) {
                            switch ($request->type) {
                                case 'yosya_kin_tax':
                                    if($request->filled('yousya_cd')) {
                                        $yousya = DB::table('m_yousya')->whereRaw(
                                        'yousya_cd = (
                                            SELECT COALESCE(siharai_cd, yousya_cd) 
                                            FROM m_yousya
                                            WHERE yousya_cd = ?
                                        )', [$request->yousya_cd])->first();
                                        if(!empty($yousya) && $yousya->zei_keisan_kbn == 3) {
                                            $yousya = DB::table('m_yousya')->where('yousya_cd', $request->yousya_cd)->first();
                                            $data = roundFromKbnTani(($request->yosya_tyukei_kin * configParam('TAX_RATE')), intval($yousya->zei_hasu_kbn), intval($yousya->zei_hasu_tani));
                                        }
                                    }
                                    
                                    break;
                                case 'seikyu_kin_tax': 
                                    if($request->filled('ninusi_cd')) {
                                        $ninusi = DB::table('m_ninusi')->whereRaw(
                                            'ninusi_cd = (
                                                SELECT COALESCE(seikyu_cd, ninusi_cd) 
                                                FROM m_ninusi
                                                WHERE ninusi_cd = ?
                                            )', [$request->ninusi_cd]
                                        )->first();
                                        if(!empty($ninusi) && $ninusi->zei_keisan_kbn == 3) {
                                            $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $request->ninusi_cd)->first();
                                            $data = roundFromKbnTani(($request->unchin_kin + $request->tyukei_kin + $request->tesuryo_kin + $request->nieki_kin + $request->syuka_kin) * configParam('TAX_RATE'), intval($ninusi->zei_hasu_kbn), intval($ninusi->zei_hasu_tani));
                                        }
                                    }
                                    
                                    break;
                                default:
                                    $data = null;
                                    break;
                            }
                        // } else {
                        //     $data = null;
                        // }
                //     }
                // }
            }
            
        }
        if($request->ajax()) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => $data
            ]);
        }
        return $data;
    }

    private function __getInitSearch() {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_SEARCH)
                ->first();
        if(empty($init)) {
            return [];
        }
        $initCopy = [];
        try {
            if($init->choice1_nm && !empty($init->choice1_char)) {
                $data = DB::table('m_bumon')->select('*')->where('bumon_cd', $init->choice1_char)->first();
                $initCopy[$init->choice1_nm] = $init->choice1_char;
                $initCopy['hed_bumon_nm'] = $data->bumon_nm;
            }

            if($init->choice2_nm && !empty($init->choice2_char)) {
                $data = DB::table('m_jyomuin')->select('*')->where('jyomuin_cd', $init->choice2_char)->first();
                $initCopy[$init->choice2_nm] = $init->choice2_char;
                $initCopy['hed_jyomuin_nm'] = $data->jyomuin_nm;
            }
            $choiceKeys = $this->__getKeyInitSearch();

            foreach ($choiceKeys as $key => $value) {

                try {
                    if (!empty($value['table'])) {
                        $cleanKey = preg_replace('/(_to|_from)$/', '', $key);

                        $condition = [$cleanKey => $init->{'choice' . $value['index'] . '_char'}];

                        if($key == 'hatuti_cd_from' || $key == 'hatuti_cd_to') {
                            $result = \DB::table(DB::raw('m_hachaku as m_hatuti'))
                                ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'))
                                ->where('hachaku_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } else if($key == 'genkin_cd_from' || $key == 'genkin_cd_to') {
                            if($init->{'choice' . $value['index'] . '_char'} == '1') {
                                $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                                $initCopy[str_replace('_cd', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = '現金';
                                continue;
                            }
                        } else if( $key == 'ninusi_cd_from' || $key == 'ninusi_cd_to') {
                            $result = \DB::table('m_ninusi')
                                ->select('*', \DB::raw('ninusi_ryaku_nm as ninusi_nm'))
                                ->where('ninusi_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } else if($key == 'syubetu_cd_from' || $key == 'syubetu_cd_to') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as syubetu_nm'), \DB::raw('meisyo_cd as syubetu_cd'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_SYUBETU'))
                                ->first();
                        } else if($key == 'hinmei_cd_from' || $key == 'hinmei_cd_to') {
                            $result = \DB::table('m_hinmei')
                                ->select('m_hinmei.*', \DB::raw('m_hinmoku.hinmoku_nm'))
                                ->join('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd')
                                ->where('m_hinmei.hinmei_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                            if(!empty($result)) {
                                $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                                $initCopy[str_replace('_cd', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->{str_replace('_cd', '_nm', $cleanKey)};
                                $initCopy[str_replace('hinmei_cd', 'hinmoku_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->hinmoku_nm;
                                continue;
                            }
                        } else if($key == 'tani') {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                            continue;
                        } else if($key == 'gyosya_cd_from' || $key == 'gyosya_cd_to') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as gyosya_nm'), \DB::raw('meisyo_cd as gyosya_cd'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                                ->first();
                        } else if($key == 'biko_cd_from' || $key == 'biko_cd_to') {
                            $result = \DB::table('m_biko')
                                ->select('*', \DB::raw('meisyo_nm as biko'))
                                ->where('biko_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } else if($key == 'yousya_cd_from' || $key == 'yousya_cd_to') {
                            $result = \DB::table('m_yousya')
                                ->select('*', \DB::raw('yousya_ryaku_nm as yousya_nm'))
                                ->where('yousya_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        }
                        else {
                            $result = DB::table($value['table'])->select('*')->where($condition)->first();
                        }

                        if(!empty($result)) {
                            $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                            $initCopy[str_replace('_cd', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->{str_replace('_cd', '_nm', $cleanKey)};
                        }

                    } else {
                        if (strpos($key, '_dt') !== false) {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_dt'};
                        } else {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                    continue;
                }
            }

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return [];
        }
        return $initCopy;
    }

    private function __getInitCopy($data) {
        if(!empty($data)) {
            $dataInit = [];
            if (is_object($data)) {
                $data = (array)$data;
            }
            for($i = 1; $i <= 100; $i++) {
                if(!empty($data['choice'.$i.'_bool'])) {
                    $dataInit[] = $data['choice'.$i.'_nm'];
                }
            }
            // If the result is 1, it means data is present, but all fields have the value false.
            return !empty($dataInit) ? $dataInit : 1;
        }
        return $this->__getDefaultInitCopy();
    }

    private function __getDefaultInitCopy() {
        $config = require(app_path('Helpers/Grid/config/t_uriage.php'));
        $data = [];
        if(is_array($config)) {
            foreach ($config as $key => $configValue) {
               if(!empty($configValue['copitable'])) {
                    $data[] = $configValue['field']; 
                    if(!empty($configValue['copyListHidden'])) {
                        foreach ($configValue['copyListHidden'] as $hide) {
                            $data[] = $hide;
                        }
                    }
               }
            }
        }
        return $data;
    }

    private function __getKeyInitSearch() {
        return [
            'hed_unso_dt_from' => [
                'index' => 3
            ],
            'hed_unso_dt_to' => [
                'index' => 4
            ],
            'bumon_cd_from' => [
                'index' => 5,
                'table' => 'm_bumon'
            ],
            'bumon_cd_to' => [
                'index' => 6,
                'table' => 'm_bumon'
            ]
            ,
            'hatuti_cd_from' => [
                'index' => 7,
                'table' => 'm_hatuti',
                'column' => 'hachaku_cd'
            ],
            'hatuti_cd_to' => [
                'index' => 8,
                'table' => 'm_hatuti',
                'column' => 'hachaku_cd'
            ],
            'genkin_cd' => [
                'index' => 9
            ], 
            // 'genkin_cd_from' => [
            //     'index' => 9,
            //     // 'table' => 'm_genkin'
            // ],
            // 'genkin_cd_to' => [
            //     'index' => 10,
            //     // 'table' => 'm_genkin'
            // ],
            'ninusi_cd_from' => [
                'index' => 11,
                'table' => 'm_ninusi'
            ],
            'ninusi_cd_to' => [
                'index' => 12,
                'table' => 'm_ninusi'
            ],
            'syuka_dt_from' => [
                'index' => 13
            ],
            'syuka_dt_to' => [
                'index' => 14
            ],
            'haitatu_dt_from' => [
                'index' => 15
            ],
            'haitatu_dt_to' => [
                'index' => 16
            ],
            'hachaku_cd_from' => [
                'index' => 17,
                'table' => 'm_hachaku'
            ],
            'hachaku_cd_to' => [
                'index' => 18,
                'table' => 'm_hachaku'
            ],
            'syubetu_cd_from' => [
                'index' => 19,
                // 'table' => 'm_syubetu',
                // 'column' => 'meisyo_cd'
            ],
            'syubetu_cd_to' => [
                'index' => 20,
                // 'table' => 'm_syubetu',
                // 'column' => 'meisyo_cd'
            ],
            'hinmei_cd_from' => [
                'index' => 21,
                'table' => 'm_hinmei'
            ],
            'hinmei_cd_to' => [
                'index' => 22,
                'table' => 'm_hinmei'
            ],
            'su_from' => [
                'index' => 23
            ],
            'su_to' => [
                'index' => 24
            ],
            'tani' => [
                'index' => 25,
                'table' => 'm_meisyo_tani',
                'column' => 'meisyo_cd'
            ],
            // 'tani_cd_from' => [
            //     'index' => 25,
            //     'table' => 'm_tani',
            //     'column' => 'meisyo_cd'
            // ],
            // 'tani_cd_to' => [
            //     'index' => 26,
            //     'table' => 'm_tani',
            //     'column' => 'meisyo_cd'
            // ],
            'unso_dt_from' => [
                'index' => 27
            ],
            'unso_dt_to' => [
                'index' => 28
            ],
            'jyotai' => [
                'index' => 29,
                'type_where' => 'like_after'
            ],
            // 'jyotai_from' => [
            //     'index' => 29
            // ],
            // 'jyotai_to' => [
            //     'index' => 30
            // ],
            'sitadori' => [
                'index' => 31,
                'type_where' => 'like_after'
            ],
            // 'sitadori_from' => [
            //     'index' => 31
            // ],
            // 'sitadori_to' => [
            //     'index' => 32
            // ],
            'gyosya_cd_from' => [
                'index' => 33,
                // 'table' => 'm_gyosya',
                // 'column' => 'meisyo_cd'
            ],
            'gyosya_cd_to' => [
                'index' => 34,
                // 'table' => 'm_gyosya',
                // 'column' => 'meisyo_cd'
            ],
            'unchin_kin_from' => [
                'index' => 35
            ],
            'unchin_kin_to' => [
                'index' => 36
            ],
            'tyukei_kin_from' => [
                'index' => 37
            ],
            'tyukei_kin_to' => [
                'index' => 38
            ],
            'tukoryo_kin_from' => [
                'index' => 39
            ],
            'tukoryo_kin_to' => [
                'index' => 40
            ],
            'syuka_kin_from' => [
                'index' => 41
            ],
            'syuka_kin_to' => [
                'index' => 42
            ],
            'tesuryo_kin_from' => [
                'index' => 43
            ],
            'tesuryo_kin_to' => [
                'index' => 44
            ],
            'biko' => [
                'index' => 45, 
                'table' => 't_uriage',
                'type_where' => 'like_after'
            ],
            // 'biko_cd_from' => [
            //     'index' => 45,
            //     'table' => 'm_biko'
            // ],
            // 'biko_cd_to' => [
            //     'index' => 46,
            //     'table' => 'm_biko'
            // ],
            'syaryo_kin_from' => [
                'index' => 47
            ],
            'syaryo_kin_to' => [
                'index' => 48
            ],
            'unten_kin_from' => [
                'index' => 49
            ],
            'unten_kin_to' => [
                'index' => 50
            ],
            'unchin_mikakutei_kbn' => [
                'index' => 51
            ],
            // 'unchin_mikakutei_kbn_from' => [
            //     'index' => 51
            // ],
            // 'unchin_mikakutei_kbn_to' => [
            //     'index' => 52
            // ],
            'syaban_from' => [
                'index' => 53
            ],
            'syaban_to' => [
                'index' => 54
            ],
            'jyomuin_cd_from' => [
                'index' => 55,
                'table' => 'm_jyomuin'
            ],
            'jyomuin_cd_to' => [
                'index' => 56,
                'table' => 'm_jyomuin'
            ],
            'yousya_cd_from' => [
                'index' => 57,
                'table' => 'm_yousya'
            ],
            'yousya_cd_to' => [
                'index' => 58,
                'table' => 'm_yousya'
            ],
            'yosya_tyukei_kin_from' => [
                'index' => 59
            ],
            'yosya_tyukei_kin_to' => [
                'index' => 60
            ],
            'yosya_tukoryo_kin_from' => [
                'index' => 61
            ],
            'yosya_tukoryo_kin_to' => [
                'index' => 62
            ],
            'yosya_kin_tax_from' => [
                'index' => 63
            ],
            'yosya_kin_tax_to' => [
                'index' => 64
            ],
            'denpyo_send_dt_from' => [
                'index' => 65
            ],
            'denpyo_send_dt_to' => [
                'index' => 66
            ],
            'nipou_dt_from' => [
                'index' => 67
            ],
            'nipou_dt_to' => [
                'index' => 68
            ],
            'nipou_no_from' => [
                'index' => 69
            ],
            'nipou_no_to' => [
                'index' => 70
            ],
            'uriage_den_no_from' => [
                'index' => 71
            ],
            'uriage_den_no_to' => [
                'index' => 72
            ]
        ];
    }

    private function __getInitColumn() {
        $data = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_COLUMN)
                ->first();
        if(empty($data)) {
            return null;
        }
        $dataInit = [];
        if (is_object($data)) {
            $data = (array)$data;
        }
        for($i = 1; $i <= 100; $i ++) {
            if(!empty($data['choice'.$i.'_bool'])) {
                $dataInit[] = $data['choice'.$i.'_nm'];
            }
        }
        return !empty($dataInit) ? $dataInit : 1;
    }
}
