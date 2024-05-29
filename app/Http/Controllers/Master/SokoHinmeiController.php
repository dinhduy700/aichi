<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use App\Http\Repositories\SokoHinmeiRepository;
use App\Helpers\XlsExportMst;
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
        $exporter = new XlsExportMst();
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');
        $listData = $this->sokoHinmeiRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();
        
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
        return Response::download($savePath)->deleteFileAfterSend(true);
    }
}
