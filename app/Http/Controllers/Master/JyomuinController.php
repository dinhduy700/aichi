<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\JyomuinRepository;
use App\Http\Requests\JyomuinRequest;
use App\Helpers\XlsExportMst;
use Illuminate\Support\Facades\File;

class JyomuinController extends Controller
{
    protected $jyomuinRepository;

    public function __construct(JyomuinRepository $jyomuinRepository) {
        $this->jyomuinRepository = $jyomuinRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_jyomuin.php'));
        return view('master.jyomuin.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('master.jyomuin.form', ['request' => $request]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JyomuinRequest $request)
    {
        $response = $this->jyomuinRepository->store($request);
        if($response['status'] == 200) {
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
    public function edit(Request $request, $jyomuinCd)
    {
        $jyomuin = $this->jyomuinRepository->getDetail($jyomuinCd);
        if(empty($jyomuin)) {
            abort(404);
        }
        return view('master.jyomuin.form', ['jyomuin' => $jyomuin, 'request' => $request]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JyomuinRequest $request, $jyomuinCd)
    {
        $response = $this->jyomuinRepository->update($request, $jyomuinCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jyomuinCd)
    {
        $response = $this->jyomuinRepository->delete($jyomuinCd);
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

        $listData = $this->jyomuinRepository->getTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json($data);
    }

    public function exportExcelDataTable (Request $request)
    {
        $listData = $this->jyomuinRepository->getTotalCount($request);
        $data = $listData['rows']->get();
        $config = require(app_path('Helpers/Excel/config/m_jyomuin.php'));
        $templatePath = app_path('Helpers/Excel/template/m_jyomuin.xlsx');

        $directoryPath = storage_path('app/download');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $savePath = $directoryPath . '/m_jyomuin.xlsx';
        $this->exportToExcel($templatePath, $config, $data, $savePath);
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    private function exportToExcel($templatePath, $config, $data, $savePath)
    {
        $exporter = new XlsExportMst();
        $exporter->export($templatePath, $config, $data, $savePath);
    }
}
