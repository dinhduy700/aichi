<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\UriageRepository;
use App\Http\Requests\OrderEntryRequest;
use App\Http\Requests\UriageFormSearchRequest;
use Illuminate\Http\Response;
use DB;

class OrderEntryController extends Controller
{	
	protected $uriageRepository;

	const URIAGE_PG_NM = 'uriage_order_entry';
    const URIAGE_FUNCTON_INIT_COPY = 'init_popup_copy';
    const URIAGE_FUNCTON_INIT_SEARCH = 'init_popup_search';
    const URIAGE_FUNCTON_INIT_COLUMN = 'init_popup_column';
    const DISPATCH_PG_NM = 'dispatch_order_entry';
    const DISPATCH_FUNCTON_INIT_COLUMN = 'init_popup_column';

    public function __construct(
        UriageRepository $uriageRepository
    ) {
        $this->uriageRepository = $uriageRepository;
    }

    public function index(Request $request)
    {
    	$setting = require(app_path('Helpers/Grid/config/t_uriage_order_entry.php'));
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
    	return view('order.order-entry.index', ['setting' => $setting, 'request' => $request, 'dataInit' => $dataInit, 'dataInitSearchPopup' => $dataInitSearchPopup, 'dataInitColumnPopup' => $dataInitColumnPopup, 'dataGenkin' => $dataGenkin, 'dataUnchinMikakutei' => $dataUnchinMikakutei]);
    }

    // 配車モード
    public function dispatch(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/t_dispatch_order_entry.php'));
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::URIAGE_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_COPY)
                ->first();
        if(!empty($init)) {
            $dataInit = $this->__getInitCopy($init);
        } else {
            $dataInit = [];
        }
        $dataInitSearchPopup = $this->__getInitSearchDispatch();
        $dataInitColumnPopup = $this->__getInitColumnDispatch(self::DISPATCH_PG_NM, self::DISPATCH_FUNCTON_INIT_COLUMN);
        $dataGenkin = DB::table('m_meisyo')->select('*', DB::raw('meisyo_cd as genkin_cd'), DB::raw('meisyo_nm as genkin_nm'))
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_GENKIN'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->get();
        $dataUnchinMikakutei = DB::table('m_meisyo')->select('*', DB::raw('meisyo_cd as unchin_mikakutei_kbn'), DB::raw('meisyo_nm as unchin_mikakutei_nm'))
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))
                    ->orderBy('meisyo_cd', 'DESC')
                    ->get();
        if (in_array("uriage_den_no", $dataInitColumnPopup)) {
            if (isset($setting["uriage_den_no"]["visible"])) {
                $setting["uriage_den_no"]["visible"] = false;
            }
        }
        return view('order.order-entry.dispatch', [
            'setting' => $setting,
            'request' => $request,
            'dataInit' => $dataInit,
            'dataInitSearchPopup' => $dataInitSearchPopup,
            'dataInitColumnPopup' => $dataInitColumnPopup,
            'dataGenkin' => $dataGenkin,
            'dataUnchinMikakutei' => $dataUnchinMikakutei
        ]);
    }

    // 配車モード:検索
    public function dispatchList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->perPage ?? 20;
        $keyInitSearch = $this->__getKeyInitSearchDispatch();
        $listData = $this->uriageRepository->getListWithTotalCount($request, $keyInitSearch);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        $dataInitColumnPopup = $this->__getInitColumnDispatch(self::DISPATCH_PG_NM, self::DISPATCH_FUNCTON_INIT_COLUMN);
        if (!empty($dataInitColumnPopup) && count($dataInitColumnPopup) > 0) {
            try {
                $filterData = [];
                foreach ($data['rows'] as $rowData) {
                    $tmpRowData = [];
                    if (!in_array("uriage_den_no", $dataInitColumnPopup)) {
                        $tmpRowData["uriage_den_no"] = $rowData->uriage_den_no;
                    }
                    for ($i = 0; $i < count($dataInitColumnPopup); $i++) {
                        $key = $dataInitColumnPopup[$i];
                        $tmpRowData[$key] = $rowData->$key;
                        if (strpos($key, "_dt") !== false && !empty($rowData->$key)) {
                            $tmpRowData[$key] = str_replace("-", "/", $rowData->$key);
                        }
                    }
                    if (count($tmpRowData) > 0) {
                        $filterData[] = $tmpRowData;
                    }
                }
                if (count($filterData) > 0) {
                    $data['rows'] = $filterData;
                }
            } catch (\Exception $e) {
                // do nothing.
            }
        } else {
            $data['rows'] = [];
        }

        return response()->json($data);
    }

    public function valdateFormSearchUriage(UriageFormSearchRequest $request)
    {   
        return response()->json([
            'status' => Response::HTTP_OK,
        ]);
    }

    // 配車モード： autocomplete取得
    public function dispatchSuggestion($type, $key='')
    {
        $data = [];
        $row = ["text" => "", "value" => ""];
        $delimiter = "§";
        $where = [];
        $orWhere = [];
        $orWhere_1 = [];
        $lq = '<span style="color: blue;">【 </span>';
        $rq = '<span style="color: blue;"> 】</span>';
        switch ($type) {
            case 'bumon_cd':
                if (strlen($key) > 0) {
                    $where[] = ["bumon_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_bumon')
                    ->select('*')
                    ->where($where)
                    ->orderBy('bumon_cd', 'ASC')
                    ->get();
                break;
            case 'syubetu_cd':
                if (strlen($key) > 0) {
                    $where[] = ["meisyo_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as syubetu_nm'), \DB::raw('meisyo_cd as syubetu_cd'))
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_SYUBETU'))
                    ->where($where)
                    ->orderBy('syubetu_cd', 'ASC')
                    ->get();
                break;
            case 'ninusi_cd':
                if (strlen($key) > 0) {
                    $where[] = ["ninusi_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_ninusi')
                    ->select('*', \DB::raw('ninusi_ryaku_nm as ninusi_nm'))
                    ->where($where)
                    ->orderBy('ninusi_cd', 'ASC')
                    ->get();
                break;
            case 'hinmei_cd':
                if (strlen($key) > 0) {
                    $where[] = ["m_hinmei.hinmei_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_hinmei')
                    ->select('m_hinmei.*', \DB::raw('m_hinmoku.hinmoku_nm'))
                    ->join('m_hinmoku', 'm_hinmoku.hinmoku_cd', 'm_hinmei.hinmoku_cd')
                    ->where($where)
                    ->orderBy('m_hinmei.hinmei_cd', 'ASC')
                    ->get();
                break;
            case 'jyomuin_cd':
                if (strlen($key) > 0) {
                    $where[] = ["jyomuin_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_jyomuin')
                    ->select('*')
                    ->where($where)
                    ->orderBy('jyomuin_cd', 'ASC')
                    ->get();
                break;
            case 'yousya_cd':
                if (strlen($key) > 0) {
                    $where[] = ["yousya_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_yousya')
                    ->select('*', \DB::raw('yousya_ryaku_nm as yousya_nm'))
                    ->where($where)
                    ->orderBy('yousya_cd', 'ASC')
                    ->get();
                break;
            case 'hachaku_cd':
                if (strlen($key) > 0) {
                    $where[] = ["hachaku_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_hachaku')
                    ->select('*')
                    ->where($where)
                    ->orderBy('hachaku_cd', 'ASC')
                    ->get();
                break;
            case 'tani_cd':
                if (strlen($key) > 0) {
                    $where[] = ["meisyo_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_meisyo')
                    ->select('*', \DB::raw('meisyo_nm as tani_nm'), \DB::raw('meisyo_cd as tani_cd'))
                    ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                    ->where($where)
                    ->orderBy('meisyo_cd', 'ASC')
                    ->get();
                break;
            case 'biko_cd':
                if (strlen($key) > 0) {
                    $where[] = ["biko_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_biko')
                    ->select('*', \DB::raw('biko_nm as biko'))
                    ->where($where)
                    ->orderBy('biko_cd', 'ASC')
                    ->get();
                break;
            case 'syaban':
                if (strlen($key) > 0) {
                    $where[] = ["syaryo_cd", "LIKE", $key.'%'];
                }
                $queryData = \DB::table('m_syaryo')
                    ->select(
                        'm_syaryo.syaryo_cd',
                        'm_syaryo.jiyo_kbn',
                        'm_syaryo.jyomuin_cd',
                        'm_jyomuin.jyomuin_nm',
                        'm_syaryo.yousya_cd',
                        'm_yousya.yousya_ryaku_nm',
                    )
                    ->leftJoin("m_jyomuin","m_jyomuin.jyomuin_cd", "=", "m_syaryo.jyomuin_cd")
                    ->leftJoin("m_yousya", "m_yousya.yousya_cd", "=", "m_syaryo.yousya_cd")
                    ->where($where)
                    ->orderBy('syaryo_cd', 'ASC')
                    ->get();
                break;
            default:
                break;
        }
        foreach ($queryData as $rowData) {
            switch ($type) {
                case 'bumon_cd':
                    $row["text"] = $lq.$rowData->bumon_cd.$rq.$lq.$rowData->bumon_nm.$rq;
                    $row["value"] = $rowData->bumon_cd.$delimiter.$rowData->bumon_nm;
                    $row["name"] = $rowData->bumon_cd;
                    $row["id"] = $rowData->bumon_cd;
                    break;
                case 'syubetu_cd':
                    $row["text"] = $lq.$rowData->syubetu_cd.$rq.$lq.$rowData->syubetu_nm.$rq;
                    $row["value"] = $rowData->syubetu_cd.$delimiter.$rowData->syubetu_nm;
                    $row["name"] = $rowData->syubetu_cd;
                    $row["id"] = $rowData->syubetu_cd;
                    break;
                case 'ninusi_cd':
                    $row["text"] = $lq.$rowData->ninusi_cd.$rq.$lq.$rowData->ninusi_nm.$rq;
                    $row["value"] = $rowData->ninusi_cd.$delimiter.$rowData->ninusi_nm;
                    $row["name"] = $rowData->ninusi_cd;
                    $row["id"] = $rowData->ninusi_cd;
                    break;
                case 'hinmei_cd':
                    $row["text"] = $lq.$rowData->hinmei_cd.$rq.$lq.$rowData->hinmoku_nm.$rq.$lq.$rowData->hinmei_nm.$rq;
                    $row["value"] = $rowData->hinmei_cd.$delimiter.$rowData->hinmoku_nm.$delimiter.$rowData->hinmei_nm;
                    $row["name"] = $rowData->hinmei_cd;
                    $row["id"] = $rowData->hinmei_cd;
                    break;
                case 'jyomuin_cd':
                    $row["text"] = $lq.$rowData->jyomuin_cd.$rq.$lq.$rowData->jyomuin_nm.$rq;
                    $row["value"] = $rowData->jyomuin_cd.$delimiter.$rowData->jyomuin_nm;
                    $row["name"] = $rowData->jyomuin_cd;
                    $row["id"] = $rowData->jyomuin_cd;
                    break;
                case 'yousya_cd':
                    $row["text"] = $lq.$rowData->yousya_cd.$rq.$lq.$rowData->yousya_nm.$rq;
                    $row["value"] = $rowData->yousya_cd.$delimiter.$rowData->yousya_nm;
                    $row["name"] = $rowData->yousya_cd;
                    $row["id"] = $rowData->yousya_cd;
                    break;
                case 'hachaku_cd':
                    $row["text"] = $lq.$rowData->hachaku_cd.$rq.$lq.$rowData->hachaku_nm.$rq;
                    $row["value"] = $rowData->hachaku_cd.$delimiter.$rowData->hachaku_nm;
                    $row["name"] = $rowData->hachaku_cd;
                    $row["id"] = $rowData->hachaku_cd;
                    break;
                case 'tani_cd':
                    $row["text"] = $lq.$rowData->tani_cd.$rq.$lq.$rowData->tani_nm.$rq;
                    $row["value"] = $rowData->tani_cd.$delimiter.$rowData->tani_nm;
                    $row["name"] = $rowData->tani_cd;
                    $row["id"] = $rowData->tani_cd;
                    break;
                case 'biko_cd':
                    $row["text"] = $lq.$rowData->biko_cd.$rq.$lq.$rowData->biko_nm.$rq;
                    $row["value"] = $rowData->biko_cd.$delimiter.$rowData->biko_nm;
                    $row["name"] = $rowData->biko_cd;
                    $row["id"] = $rowData->biko_cd;
                    break;
                case 'syaban':
                    if ($rowData->jiyo_kbn === '0') { //　自社
                        $row["text"] = $lq.$rowData->syaryo_cd.$rq.$lq.$rowData->jyomuin_cd.$rq.$lq.$rowData->jyomuin_nm.$rq;
                        $row["value"] = $rowData->syaryo_cd.$delimiter.$rowData->jiyo_kbn.$delimiter.$rowData->jyomuin_cd.$delimiter.$rowData->jyomuin_nm;
                    } else if ($rowData->jiyo_kbn === '1') { // 庸車
                        $row["text"] = $lq.$rowData->syaryo_cd.$rq.$lq.$rowData->yousya_cd.$rq.$lq.$rowData->yousya_ryaku_nm.$rq;
                        $row["value"] = $rowData->syaryo_cd.$delimiter.$rowData->jiyo_kbn.$delimiter.$rowData->yousya_cd.$delimiter.$rowData->yousya_ryaku_nm;
                    } else {
                        $row["text"] = $lq.$rowData->syaryo_cd.$rq;
                        $row["value"] = $rowData->syaryo_cd;
                    }
                    $row["name"] = $rowData->syaryo_cd;
                    $row["id"] = $rowData->syaryo_cd;
                    break;
            }
            $data[] = $row;
        }
        return Response()->json($data);
    }

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

    public function valdateRow(OrderEntryRequest $request) {
        return response()->json([
            'status' => 200,
            'data' => $request->all()
        ]);
    }

     public function updateDataTable(Request $request) 
    {
        $result = $this->uriageRepository->updateDataTable($request);
        return response()->json($result);
    }

    public function updateDataTableDispatch(Request $request)
    {
        if (isset($_FILES['data']) && !$_FILES['data']['error']){
          $data = file_get_contents($_FILES['data']['tmp_name']);
          $gridData = json_decode($data, true);
          $maxUpdate = 30;
          $count = 0;
          $curGridData = [];
          $result = [];
          for ($i = 0; $i < count($gridData); $i++) {
              $curGridData[] = $gridData[$i];
              $count++;
              if ($count == $maxUpdate || ($i + 1) >= count($gridData)) {
                  $objData = (object) ['list' => []];
                  $objData->list = $curGridData;
                  $result = $this->uriageRepository->updateDataTable($objData);
                  $curGridData = [];
                  $count = 0;
              }
              if (isset($result['status']) && $result['status'] == Response::HTTP_INTERNAL_SERVER_ERROR) {
                  break;
              }
          }
          return response()->json($result);
        }
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
        
        $data['choice2_nm'] = 'hed_add_tanto_cd';
        $data['choice2_char'] = $all['hed_add_tanto_cd'];

        $data['choice5_nm'] = 'hed_jyomuin_cd';
        $data['choice5_char'] =  $all['hed_jyomuin_cd'];
        // $data['choice6_nm'] = 'hed_jyutyu_kbn';
        // $data['choice6_char'] =  $all['hed_jyutyu_kbn'];
        $data['choice7_nm'] = 'radio_hed_syaban';
        $data['choice7_char'] = $all['radio_hed_syaban'] ?? null;

        $data['choice8_nm'] = 'hed_haitatu_dt_from';
        $data['choice8_dt'] = $all['hed_haitatu_dt_from'];


        unset($all['hed_bumon_cd']);
        unset($all['hed_jyomuin_cd']);
        if($request->filled('radio_hed_syaban')) {
            unset($all['radio_hed_syaban']);
        }

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

    public function updateInitColumnDispatch(Request $request)
    {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::DISPATCH_PG_NM)
                ->where('function', self::DISPATCH_FUNCTON_INIT_COLUMN)
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
                    ->where('pg_nm', self::DISPATCH_PG_NM)
                    ->where('function', self::DISPATCH_FUNCTON_INIT_COLUMN)
                    ->update($data);
            } else {
                $data['user_cd'] = \Auth::id();
                $data['pg_nm'] = self::DISPATCH_PG_NM;
                $data['function'] = self ::DISPATCH_FUNCTON_INIT_COLUMN;
                DB::table('m_user_pg_function')
                    ->insert($data);
            }
            $dataInit = $this->__getInitColumnDispatch(self::DISPATCH_PG_NM, self ::DISPATCH_FUNCTON_INIT_COLUMN);
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

    public function updateInitSearchDispatch(UriageFormSearchRequest $request) {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::DISPATCH_PG_NM)
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
        
        $data['choice2_nm'] = 'hed_add_tanto_cd';
        $data['choice2_char'] = $all['hed_add_tanto_cd'];

        $data['choice5_nm'] = 'hed_jyomuin_cd';
        $data['choice5_char'] =  $all['hed_jyomuin_cd'];

        $data['choice7_nm'] = 'radio_hed_syaban';
        $data['choice7_char'] = $all['radio_hed_syaban'] ?? null;

        $data['choice8_nm'] = 'hed_haitatu_dt_from';
        $data['choice8_dt'] = $all['hed_haitatu_dt_from'];

        unset($all['hed_bumon_cd']);
        unset($all['hed_jyomuin_cd']);
        if($request->filled('radio_hed_syaban')) {
            unset($all['radio_hed_syaban']);
        }

        if(!empty($all['chk'])) {
            $chk = $all['chk'];
            unset($all['chk']);
        }
        $inputs = collect($all)->filter(function ($value, $key) {
            return strpos($key, '_nm') === false;
        })->all();

        $choiceKeys = $this->__getKeyInitSearchDispatch();
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
                ->where('pg_nm', self::DISPATCH_PG_NM)
                ->where('function', self::URIAGE_FUNCTON_INIT_SEARCH)
                ->update($data);
        } else {
            $data['user_cd'] = \Auth::id();
            $data['pg_nm'] = self::DISPATCH_PG_NM;
            $data['function'] = self ::URIAGE_FUNCTON_INIT_SEARCH;
            DB::table('m_user_pg_function')
                ->insert($data);
        }

        return response()->json(['status' => 200, 'data'=> $this->__getInitSearchDispatch()]);
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
                $initCopy['hed_add_tanto_nm'] = $data->jyomuin_nm;
            }
            if($init->choice5_nm && !empty($init->choice5_char)) {
                $data = DB::table('m_jyomuin')->select('*')->where('jyomuin_cd', $init->choice5_char)->first();
                $initCopy[$init->choice5_nm] = $init->choice5_char;
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
                            $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                            if(!empty($result)) {
                                $initCopy[str_replace('_cd', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->{str_replace('_cd', '_nm', $cleanKey)};
                                $initCopy[str_replace('hinmei_cd', 'hinmoku_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->hinmoku_nm;
                            }
                            continue;
                        } /*else if($key == 'tani') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as tani_nm'), \DB::raw('meisyo_cd as tani_cd'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                                ->first();
                        } */
                        else if($key == 'tani') {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                            continue;
                        }
                        else if($key == 'gyosya_cd_from' || $key == 'gyosya_cd_to') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as gyosya_nm'), \DB::raw('meisyo_cd as gyosya_cd'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                                ->first();
                        } /* else if($key == 'biko_cd_from' || $key == 'biko_cd_to') {
                            $result = \DB::table('m_biko')
                                ->select('*', \DB::raw('meisyo_nm as biko'))
                                ->where('biko_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } */
                        else if($key == 'biko') {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                            continue;
                        } else if($key == 'tyuki') {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                            continue;
                        } else if($key == 'yousya_cd_from' || $key == 'yousya_cd_to') {
                            $result = \DB::table('m_yousya')
                                ->select('*', \DB::raw('yousya_ryaku_nm as yousya_nm'))
                                ->where('yousya_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } else if($key == 'hed_jyutyu_kbn') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as hed_jyutyu_nm'), \DB::raw('meisyo_cd as jyutyu_kbn'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'))
                                ->first();
                            if(!empty($result)) {
                                $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                                $initCopy[str_replace('_kbn', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->{str_replace('_kbn', '_nm', $cleanKey)};
                                continue;
                            }
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

    private function __getInitSearchDispatch() {
        $init = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::DISPATCH_PG_NM)
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
                $initCopy['hed_add_tanto_nm'] = $data->jyomuin_nm;
            }
            if($init->choice5_nm && !empty($init->choice5_char)) {
                $data = DB::table('m_jyomuin')->select('*')->where('jyomuin_cd', $init->choice5_char)->first();
                $initCopy[$init->choice5_nm] = $init->choice5_char;
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
                            $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                            if(!empty($result)) {
                                $initCopy[str_replace('_cd', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->{str_replace('_cd', '_nm', $cleanKey)};
                                $initCopy[str_replace('hinmei_cd', 'hinmoku_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->hinmoku_nm;
                            }
                            continue;
                        } /*else if($key == 'tani') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as tani_nm'), \DB::raw('meisyo_cd as tani_cd'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                                ->first();
                        } */
                        else if($key == 'tani') {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                            continue;
                        }
                        else if($key == 'gyosya_cd_from' || $key == 'gyosya_cd_to') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as gyosya_nm'), \DB::raw('meisyo_cd as gyosya_cd'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                                ->first();
                        } /* else if($key == 'biko_cd_from' || $key == 'biko_cd_to') {
                            $result = \DB::table('m_biko')
                                ->select('*', \DB::raw('meisyo_nm as biko'))
                                ->where('biko_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } */
                        else if($key == 'biko') {
                            $initCopy[$key] = $init->{'choice'. $value['index'] . '_char'};
                            continue;
                        } else if($key == 'yousya_cd_from' || $key == 'yousya_cd_to') {
                            $result = \DB::table('m_yousya')
                                ->select('*', \DB::raw('yousya_ryaku_nm as yousya_nm'))
                                ->where('yousya_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->first();
                        } else if($key == 'hed_jyutyu_kbn') {
                            $result = \DB::table('m_meisyo')
                                ->select('*', \DB::raw('meisyo_nm as hed_jyutyu_nm'), \DB::raw('meisyo_cd as jyutyu_kbn'))
                                ->where('meisyo_cd', $init->{'choice' . $value['index'] .'_char'})
                                ->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'))
                                ->first();
                            if(!empty($result)) {
                                $initCopy[$init->{'choice' . $value['index'].'_nm'}] = $init->{'choice' . $value['index'] . '_char'};
                                $initCopy[str_replace('_kbn', '_nm', $init->{'choice' . $value['index'] . '_nm'})] = $result->{str_replace('_kbn', '_nm', $cleanKey)};
                                continue;
                            }
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
        $config = require(app_path('Helpers/Grid/config/t_uriage_order_entry.php'));
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
            'hed_jyutyu_kbn' => [
                'index' => 6,
                'table' => 'm_meisyo_jyutyu',
                'column' => 'meisyo_cd'
            ],
            'radio_hed_syaban' => [
                'index' => 7
            ],
            'hed_haitatu_dt_from' => [
                'index' => 8
            ],
            'hed_haitatu_dt_to' => [
                'index' => 9
            ],
            'bumon_cd_from' => [
                'index' => 10,
                'table' => 'm_bumon'
            ],
            'bumon_cd_to' => [
                'index' => 11,
                'table' => 'm_bumon'
            ],
            'hatuti_cd_from' => [
                'index' => 12,
                'table' => 'm_hatuti',
                'column' => 'hachaku_cd'
            ],
            'hatuti_cd_to' => [
                'index' => 13,
                'table' => 'm_hatuti',
                'column' => 'hachaku_cd'
            ],
            'genkin_cd' => [
                'index' => 14
            ], 
            'ninusi_cd_from' => [
                'index' => 15,
                'table' => 'm_ninusi'
            ],
            'ninusi_cd_to' => [
                'index' => 16,
                'table' => 'm_ninusi'
            ],
            'syuka_dt_from' => [
                'index' => 17
            ],
            'syuka_dt_to' => [
                'index' => 18
            ],
            'haitatu_dt_from' => [
                'index' => 19
            ],
            'haitatu_dt_to' => [
                'index' => 20
            ],
            'hachaku_cd_from' => [
                'index' => 21,
                'table' => 'm_hachaku'
            ],
            'hachaku_cd_to' => [
                'index' => 22,
                'table' => 'm_hachaku'
            ],
            'hinmei_cd_from' => [
                'index' => 23,
                'table' => 'm_hinmei'
            ],
            'hinmei_cd_to' => [
                'index' => 24,
                'table' => 'm_hinmei'
            ],
            'su_from' => [
                'index' => 25
            ],
            'su_to' => [
                'index' => 26
            ],
            'tani' => [
                'index' => 27,
                'table' => 'm_meisyo_tani',
                'column' => 'meisyo_cd'
            ],

            'jyotai' => [
                'index' => 28,
                'type_where' => 'like_after'
            ],

            'sitadori' => [
                'index' => 29,
                'type_where' => 'like_after'
            ],

            'gyosya_cd_from' => [
                'index' => 30,
                'table' => 'm_meisyo_gyosya',
                'column' => 'meisyo_cd'
            ],
            'gyosya_cd_to' => [
                'index' => 31,
                'table' => 'm_meisyo_gyosya',
                'column' => 'meisyo_cd'
            ],
            'tyuki' => [
                'index' => 32,
                'table' => 't_uriage',
                'type_where' => 'like_after'
            ],
            'tanka_kbn_from' => [
                'index' => 34,
                // 'table' => 'm_gyosya',
                // 'column' => 'meisyo_cd'
            ],
            'tanka_kbn_to' => [
                'index' => 35,
                // 'table' => 'm_gyosya',
                // 'column' => 'meisyo_cd'
            ],
            'seikyu_tanka_from' => [
                'index' => 36,
                // 'table' => 'm_gyosya',
                // 'column' => 'meisyo_cd'
            ],
            'seikyu_tanka_to' => [
                'index' => 37,
                // 'table' => 'm_gyosya',
                // 'column' => 'meisyo_cd'
            ],

            'unchin_kin_from' => [
                'index' => 38
            ],
            'unchin_kin_to' => [
                'index' => 39
            ],
            'tyukei_kin_from' => [
                'index' => 40
            ],
            'tyukei_kin_to' => [
                'index' => 41
            ],
            'tukoryo_kin_from' => [
                'index' => 42
            ],
            'tukoryo_kin_to' => [
                'index' => 43
            ],
            'syuka_kin_from' => [
                'index' => 44
            ],
            'syuka_kin_to' => [
                'index' => 45
            ],
            'tesuryo_kin_from' => [
                'index' => 46
            ],
            'tesuryo_kin_to' => [
                'index' => 47
            ],
            'biko' => [
                'index' => 48, 
                'table' => 't_uriage',
                'type_where' => 'like_after'
            ],
            'syaryo_kin_from' => [
                'index' => 49
            ],
            'syaryo_kin_to' => [
                'index' => 50
            ],
            'unten_kin_from' => [
                'index' => 51
            ],
            'unten_kin_to' => [
                'index' => 52
            ],
            'unchin_mikakutei_kbn' => [
                'index' => 53
            ],
            'yousya_cd_from' => [
                'index' => 54,
                'table' => 'm_yousya'
            ],
            'yousya_cd_to' => [
                'index' => 55,
                'table' => 'm_yousya'
            ],
            'yosya_tyukei_kin_from' => [
                'index' => 56
            ],
            'yosya_tyukei_kin_to' => [
                'index' => 57
            ],
            'yosya_tukoryo_kin_from' => [
                'index' => 58
            ],
            'yosya_tukoryo_kin_to' => [
                'index' => 59
            ],
            'okurijyo_no_from' => [
                'index' => 60
            ],
            'okurijyo_no_to' => [
                'index' => 61
            ],
            'jyutyu_kbn_from' => [
                'index' => 62
            ],
            'jyutyu_kbn_to' => [
                'index' => 63
            ],
            'kaisyu_dt_from' => [
                'index' => 64
            ],
            'kaisyu_dt_to' => [
                'index' => 65
            ],
            'kaisyu_kin_from' => [
                'index' => 66
            ],
            'kaisyu_kin_to' => [
                'index' => 67
            ],
            // 'tukoryo_kin_from' => [
            //     'index' => 68
            // ],
            // 'tukoryo_kin_to' => [
            //     'index' => 69
            // ],
            'add_tanto_cd_from' => [
                'index' => 70,
                'table' => 't_uriage',
                'column' => 'add_tanto_cd'
            ],
            'add_tanto_cd_to' => [
                'index' => 71,
                'table' => 't_uriage',
                'column' => 'add_tanto_cd'
            ],
            'uriage_den_no_from' => [
                'index' => 72
            ],
            'uriage_den_no_to' => [
                'index' => 73
            ],
            'haitatu_tel_from' => [
                'index' => 74
            ],
            'haitatu_tel_to' => [
                'index' => 75
            ],
            'hed_syuka_dt_from' => [
                'index' => 76,
            ],
            'hed_syuka_dt_to' => [
                'index' => 77,
            ]
        ];
    }

    private function __getKeyInitSearchDispatch() {
        return [
            'hed_unso_dt_from' => [
                'index' => 3
            ],
            'hed_unso_dt_to' => [
                'index' => 4
            ],
            'hed_jyutyu_kbn' => [
                'index' => 6,
                'table' => 'm_meisyo_jyutyu',
                'column' => 'meisyo_cd'
            ],
            'radio_hed_syaban' => [
                'index' => 7
            ],
            'hed_haitatu_dt_from' => [
                'index' => 8
            ],
            'hed_haitatu_dt_to' => [
                'index' => 9
            ],
            'syaban_from' => [
                'index' => 10,
                'table' => 't_uriage',
            ],
            'syaban_to' => [
                'index' => 11,
                'table' => 't_uriage',
            ],
            'jyomuin_cd_from' => [
                'index' => 12,
                'table' => 't_uriage',
            ],
            'jyomuin_cd_to' => [
                'index' => 13,
                'table' => 't_uriage',
            ],
            'yousya_cd_from' => [
                'index' => 14,
                'table' => 'm_yousya'
            ],
            'yousya_cd_to' => [
                'index' => 15,
                'table' => 'm_yousya'
            ],
            'ninusi_cd_from' => [
                'index' => 16,
                'table' => 'm_ninusi'
            ],
            'ninusi_cd_to' => [
                'index' => 17,
                'table' => 'm_ninusi'
            ],
            'hachaku_cd_from' => [
                'index' => 18,
                'table' => 'm_hachaku'
            ],
            'hachaku_cd_to' => [
                'index' => 19,
                'table' => 'm_hachaku'
            ],
            'haitatu_dt_from' => [
                'index' => 20
            ],
            'haitatu_dt_to' => [
                'index' => 21
            ],
            'jikoku_from' => [
                'index' => 22
            ],
            'jikoku_to' => [
                'index' => 23
            ],
            'tyuki_from' => [
                'index' => 24,
            ],
            'tyuki_to' => [
                'index' => 25,
            ],
            'haitatu_jyusyo1_from' => [
                'index' => 26,
            ],
            'haitatu_jyusyo1_to' => [
                'index' => 27,
            ],
            'haitatu_jyusyo2_from' => [
                'index' => 28,
            ],
            'haitatu_jyusyo2_to' => [
                'index' => 29,
            ],
            'haitatu_atena_from' => [
                'index' => 30,
            ],
            'haitatu_atena_to' => [
                'index' => 31,
            ],
            'haitatu_tel_from' => [
                'index' => 32,
            ],
            'haitatu_tel_to' => [
                'index' => 33,
            ],
            'haitatu_fax_from' => [
                'index' => 34,
            ],
            'haitatu_fax_to' => [
                'index' => 35,
            ],
            'unso_dt_from' => [
                'index' => 36
            ],
            'unso_dt_to' => [
                'index' => 37
            ],
            'jisya_km_from' => [
                'index' => 38
            ],
            'jisya_km_to' => [
                'index' => 39
            ],
            'hinmei_cd_from' => [
                'index' => 40,
                'table' => 'm_hinmei'
            ],
            'hinmei_cd_to' => [
                'index' => 41,
                'table' => 'm_hinmei'
            ],
            'su_from' => [
                'index' => 42
            ],
            'su_to' => [
                'index' => 43
            ],
            'tani' => [
                'index' => 44,
                'table' => 'm_meisyo_tani',
                'column' => 'meisyo_cd'
            ],
            'unchin_kin_from' => [
                'index' => 45
            ],
            'unchin_kin_to' => [
                'index' => 46
            ],
            'tyukei_kin_from' => [
                'index' => 47
            ],
            'tyukei_kin_to' => [
                'index' => 48
            ],
            'tukoryo_kin_from' => [
                'index' => 49
            ],
            'tukoryo_kin_to' => [
                'index' => 50
            ],
            'syuka_kin_from' => [
                'index' => 51
            ],
            'syuka_kin_to' => [
                'index' => 52
            ],
            'syaryo_kin_from' => [
                'index' => 53
            ],
            'syaryo_kin_to' => [
                'index' => 54
            ],
            'unten_kin_from' => [
                'index' => 55
            ],
            'unten_kin_to' => [
                'index' => 56
            ],
            'yosya_tyukei_kin_from' => [
                'index' => 57
            ],
            'yosya_tyukei_kin_to' => [
                'index' => 58
            ],
            'yosya_tukoryo_kin_from' => [
                'index' => 59
            ],
            'yosya_tukoryo_kin_to' => [
                'index' => 60
            ],
            'biko' => [
                'index' => 61, 
                'table' => 't_uriage',
                'type_where' => 'like_after'
            ],
            'add_tanto_cd_from' => [
                'index' => 62,
                'table' => 't_uriage',
            ],
            'add_tanto_cd_to' => [
                'index' => 63,
                'table' => 't_uriage',
            ],
            'uriage_den_no_from' => [
                'index' => 64,
            ],
            'uriage_den_no_to' => [
                'index' => 65,
            ],
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

    private function __getInitColumnDispatch($pg_nm, $function)
    {
        $data = DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', $pg_nm)
                ->where('function', $function)
                ->first();
        $dataInit = [];
        if (is_object($data)) {
            $data = (array)$data;
        }
        for($i = 1; $i <= 100; $i ++) {
            if(!empty($data['choice'.$i.'_bool'])) {
                $dataInit[] = $data['choice'.$i.'_nm'];
            }
        }
        return $dataInit;
    }
}
