<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\HinmokuRepository;
use App\Helpers\XlsExportMst;
use App\Http\Requests\HinmokuRequest;
use Illuminate\Support\Facades\File;

class HinmokuController extends Controller
{
    protected $hinmokuRepository;
    public function __construct(HinmokuRepository $hinmokuRepository) {
        $this->hinmokuRepository = $hinmokuRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_hinmoku.php'));
        return view('master.hinmoku.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('master.hinmoku.form', ['request' => $request]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HinmokuRequest $request)
    {
        $response = $this->hinmokuRepository->store($request);
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
    public function edit(Request $request, $hinmokuCd)
    {
        $hinmoku = $this->hinmokuRepository->getDetail($hinmokuCd);
        if(empty($hinmoku)) {
            abort(404);
        }
        return view('master.hinmoku.form', ['hinmoku' => $hinmoku, 'request' => $request]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HinmokuRequest $request, $hinmokuCd)
    {
        $response = $this->hinmokuRepository->update($request, $hinmokuCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hinmokuCd)
    {
        $response = $this->hinmokuRepository->delete($hinmokuCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }

    public function dataList(Request $request) 
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->hinmokuRepository->getTotalCount($request);
        
        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        
        return response()->json($data);
    }
    
    public function exportExcelDataTable (Request $request) 
    {
        $listData = $this->hinmokuRepository->getTotalCount($request);
        $data = $listData['rows']->get();
        $config = require(app_path('Helpers/Excel/config/m_hinmoku.php'));
        $templatePath = app_path('Helpers/Excel/template/m_hinmoku.xlsx');

        $directoryPath = storage_path('app/download');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $savePath = $directoryPath . '/m_hinmoku.xlsx';
        $this->exportToExcel($templatePath, $config, $data, $savePath);
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    private function exportToExcel($templatePath, $config, $data, $savePath)
    {
        $exporter = new XlsExportMst();
        $exporter->export($templatePath, $config, $data, $savePath);
    }
}
