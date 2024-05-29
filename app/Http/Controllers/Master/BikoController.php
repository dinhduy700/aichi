<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use App\Http\Repositories\BikoRepository;
use App\Helpers\XlsExportMst;
use App\Http\Requests\BikoRequest;

class BikoController extends Controller
{
    protected $bikoRepository;

    public function __construct(
      BikoRepository $bikoRepository
    ) {
        $this->bikoRepository = $bikoRepository;
    }

    /**
     * Display the biko index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_biko.php'));
        return view('master.biko.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Display the form for creating a new biko.
     *
     * @param  \App\Http\Requests\BikoRequest  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {

        return view('master.biko.form', ['request' => $request]);
    }

    /**
     * Store a newly created biko in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BikoRequest $request)
    {
        $response = $this->bikoRepository->store($request);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Display the specified biko.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {

    }

    /**
     * Display the form for editing the specified Biko.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $bikoCd
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $bikoCd)
    {
        $biko = $this->bikoRepository->getDetail($bikoCd);
        if(empty($biko)) {
            abort(404);
        }
        return view('master.biko.form', ['biko' => $biko, 'request' => $request]);
    }

    /**
     * Update the specified biko in storage.
     *
     * @param  \App\Http\Requests\MeisyoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BikoRequest $request, $bikoCd)
    {
        $response = $this->bikoRepository->update($request, $bikoCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified biko from storage.
     *
     * @param  int  $bikoCd
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($bikoCd)
    {
        $response = $this->bikoRepository->delete($bikoCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }

    /**
     * Get a list with total count of Biko data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->bikoRepository->getListWithTotalCount($request);

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
        $listData = $this->bikoRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();
        
        $config = require(app_path('Helpers/Excel/config/m_biko.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_biko.xlsx');
        $savePath = $directoryPath . '/m_biko.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }
}
