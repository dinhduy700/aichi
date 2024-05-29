<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\SokoRepository;
use App\Http\Requests\SokoRequest;
use App\Helpers\XlsExportMst;
use Illuminate\Support\Facades\File;
class SokoController extends Controller
{
    protected $sokoRepository;
    public function __construct(SokoRepository $sokoRepository)
    {
        $this->sokoRepository = $sokoRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_soko.php'));
        return view('master.soko.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('master.soko.form', ['request' => $request]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SokoRequest $request)
    {
        $response = $this->sokoRepository->store($request);
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
    public function edit(Request $request, $sokoCd, $bumonCd)
    {
        $soko = $this->sokoRepository->getDetail($sokoCd, $bumonCd);
        if (empty($soko)) {
            abort(404);
        }
        return view('master.soko.form', ['soko' => $soko, 'request' => $request]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SokoRequest $request, $sokoCd, $bumonCd)
    {
        $response = $this->sokoRepository->update($request, $sokoCd, $bumonCd);
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sokoCd, $bumonCd)
    {
        $response = $this->sokoRepository->delete($sokoCd, $bumonCd);
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }
    /**
     * Get a paginated list of soko data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->sokoRepository->getTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json($data);
    }

    public function exportExcelDataTable(Request $request)
    {
        $listData = $this->sokoRepository->getTotalCount($request);
        $data = $listData['rows']->get();
        $config = require(app_path('Helpers/Excel/config/m_soko.php'));
        $templatePath = app_path('Helpers/Excel/template/m_soko.xlsx');

        $directoryPath = storage_path('app/download');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $savePath = $directoryPath . '/m_soko.xlsx';
        $this->exportToExcel($templatePath, $config, $data, $savePath);
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    private function exportToExcel($templatePath, $config, $data, $savePath)
    {
        $exporter = new XlsExportMst();
        $exporter->export($templatePath, $config, $data, $savePath);
    }
}
