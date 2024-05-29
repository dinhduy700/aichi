<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Repositories\HinmeiRepository;
use Illuminate\Http\Request;
use App\Helpers\XlsExportMst;
use App\Http\Requests\HinmeiRequest;
use Illuminate\Support\Facades\File;

class HinmeiController extends Controller
{
    protected $hinmeiRepository;
    public function __construct(HinmeiRepository $hinmeiRepository)
    {
        $this->hinmeiRepository = $hinmeiRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_hinmei.php'));
        return view('master.hinmei.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('master.hinmei.form', ['request' => $request]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HinmeiRequest $request)
    {
        $response = $this->hinmeiRepository->store($request);
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $hinmeiCd)
    {
        $hinmei = $this->hinmeiRepository->getDetail($hinmeiCd);
        if (empty($hinmei)) {
            abort(404);
        }
        return view('master.hinmei.form', ['hinmei' => $hinmei, 'request' => $request]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HinmeiRequest $request, $hinmeiCd)
    {
        $response = $this->hinmeiRepository->update($request, $hinmeiCd);
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hinmeiCd)
    {
        $response = $this->hinmeiRepository->delete($hinmeiCd);
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }
    /**
     * Get a paginated list of hinmei data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->hinmeiRepository->getTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json($data);
    }

    public function exportExcelDataTable(Request $request)
    {
        $listData = $this->hinmeiRepository->getTotalCount($request);
        $data = $listData['rows']->get();
        $config = require(app_path('Helpers/Excel/config/m_hinmei.php'));
        $templatePath = app_path('Helpers/Excel/template/m_hinmei.xlsx');

        $directoryPath = storage_path('app/download');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $savePath = $directoryPath . '/m_hinmei.xlsx';
        $this->exportToExcel($templatePath, $config, $data, $savePath);
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    private function exportToExcel($templatePath, $config, $data, $savePath)
    {
        $exporter = new XlsExportMst();
        $exporter->export($templatePath, $config, $data, $savePath);
    }
}
