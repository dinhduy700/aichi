<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use App\Http\Repositories\SokoHinmeiRepository;
use App\Helpers\Excel\Master\XlsSokoHinmeiList;
use App\Http\Requests\SokoHinmeiRequest;

class SokoHinmeiController extends Controller
{
    protected $sokoHinmeiRepository;

    public function __construct(
      SokoHinmeiRepository $sokoHinmeiRepository
    ) {
        $this->sokoHinmeiRepository = $sokoHinmeiRepository;
    }

    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_soko_hinmei.php'));
        return view('master.soko-hinmei.index', ['setting' => $setting, 'request' => $request]);
    }

    public function create(Request $request)
    {
        return view('master.soko-hinmei.form', ['request' => $request, 'mode' => 'create']);
    }

    public function store(SokoHinmeiRequest $request)
    {
        $response = $this->sokoHinmeiRepository->store($request);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    public function edit(Request $request, $ninusiCd, $hinmeiCd)
    {
        $sokoHinmei = $this->sokoHinmeiRepository->getDetail($ninusiCd, $hinmeiCd);
        if(empty($sokoHinmei)) {
            abort(404);
        }
        return view('master.soko-hinmei.form', ['sokoHinmei' => $sokoHinmei, 'request' => $request, 'mode' => 'edit']);
    }

    public function update(SokoHinmeiRequest $request, $ninusiCd, $hinmeiCd)
    {
        $response = $this->sokoHinmeiRepository->update($request, $ninusiCd, $hinmeiCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    
    public function destroy($ninusiCd, $hinmeiCd)
    {
        $response = $this->sokoHinmeiRepository->delete($ninusiCd, $hinmeiCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }

    public function copy(Request $request, $ninusiCd, $hinmeiCd)
    {
        $sokoHinmei = $this->sokoHinmeiRepository->getDetail($ninusiCd, $hinmeiCd);

        if(empty($sokoHinmei)) {
            abort(404);
        }

        return view('master.soko-hinmei.form', ['sokoHinmei' => $sokoHinmei, 'request' => $request, 'mode' => 'copy']);
    }

    public function postCopy(SokoHinmeiRequest $request)
    {
        $response = $this->sokoHinmeiRepository->store($request);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Get a list with total count of soko-hinmei data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->sokoHinmeiRepository->getListWithTotalCount($request);

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
    public function exportExcelDataTable(Request $request)
    {
        $exporter = new XlsSokoHinmeiList();
        $listData = $this->sokoHinmeiRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();

        foreach ($data as $i => $item) {
            $item->ondo         = $item->ondo != null &&  $item->ondo != '' ? data_get(configParam('options.m_soko_hinmei.ondo', [], 1), $item->ondo) : null;
            $item->zaiko_kbn    = $item->zaiko_kbn != null &&  $item->zaiko_kbn != '' ? data_get(configParam('options.m_soko_hinmei.zaiko_kbn', [], 1), $item->zaiko_kbn) : null;
            $item->keisan_kb    = $item->keisan_kb != null &&  $item->keisan_kb != '' ? data_get(configParam('options.m_soko_hinmei.keisan_kb', [], 1), $item->keisan_kb) : null;
            $item->seikyu_keta  = $item->seikyu_keta != null &&  $item->seikyu_keta != '' ? data_get(configParam('options.m_soko_hinmei.seikyu_keta', [], 1), $item->seikyu_keta) : null;
            $item->kyumin_flg   = $item->kyumin_flg != null &&  $item->kyumin_flg != '' ? data_get(configParam('options.m_soko_hinmei.kyumin_flg', [], 1), $item->kyumin_flg) : null;
        }
       
        $config = require(app_path('Helpers/Excel/config/m_soko_hinmei.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_soko_hinmei.xlsx');
        $savePath = $directoryPath . '/m_soko_hinmei.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);

        return response()->download($savePath, '倉庫商品マスタリスト_'.  date('YmdHis') . '.xlsx')
            ->deleteFileAfterSend(true);
    }
}
