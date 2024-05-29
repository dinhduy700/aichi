<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Repositories\MeisyoRepository;
use Illuminate\Support\Carbon;
use App\Helpers\XlsExportMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\MeisyoRequest;
use Illuminate\Support\Facades\File;

class MeisyoController extends Controller
{
    protected $meisyoRepository;

    public function __construct(
        MeisyoRepository $meisyoRepository
    ) {
        $this->meisyoRepository = $meisyoRepository;
    }

    /**
     * Display the Meisyo index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_meisyo.php'));
        return view('master.meisyo.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Display the form for creating a new Meisyo.
     *
     * @param  \App\Http\Requests\MeisyoRequest  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {

        return view('master.meisyo.form', ['request' => $request]);
    }

    /**
     * Store a newly created Meisyo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeisyoRequest $request)
    {
        $response = $this->meisyoRepository->store($request);
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
    public function edit(Request $request, $meisyoKbn, $meisyoCd)
    {
        $meisyo = $this->meisyoRepository->getDetail(urldecode($meisyoKbn), urldecode($meisyoCd));
        if(empty($meisyo)) {
            abort(404);
        }
        return view('master.meisyo.form', ['meisyo' => $meisyo, 'request' => $request]);
    }

    /**
     * Update the specified Meisyo in storage.
     *
     * @param  \App\Http\Requests\MeisyoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MeisyoRequest $request, $meisyoKbn, $meisyoCd)
    {
        $response = $this->meisyoRepository->update($request, $meisyoKbn, $meisyoCd);
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
    public function destroy($meisyoKbn, $meisyoCd)
    {
        $response = $this->meisyoRepository->delete($meisyoKbn, $meisyoCd);
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

        $listData = $this->meisyoRepository->getListWithTotalCount($request);

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
        $listData = $this->meisyoRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();

        $config = require(app_path('Helpers/Excel/config/m_meisyo.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_meisyo.xlsx');
        $savePath = $directoryPath . '/m_meisyo.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }

    private function _demoPrivate() {
        return true;
    }
}
