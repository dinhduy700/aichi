<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use App\Http\Repositories\HachakuRepository;
use App\Helpers\XlsExportMst;
use App\Http\Requests\HachakuRequest;

class HachakuController extends Controller
{
    protected $hachakuRepository;

    public function __construct(
        HachakuRepository $hachakuRepository
    ) {
        $this->hachakuRepository = $hachakuRepository;
    }

    /**
     * Display the Hachaku index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_hachaku.php'));
        return view('master.hachaku.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Display the form for creating a new Meisyo.
     *
     * @param  \App\Http\Requests\HachakuRequest  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {

        return view('master.hachaku.form', ['request' => $request]);
    }

    /**
     * Store a newly created Hachaku in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HachakuRequest $request)
    {
        $response = $this->hachakuRepository->store($request);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Display the specified Hachaku.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {

    }

    /**
     * Display the form for editing the specified Hachaku.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $hachakuCd
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $hachakuCd)
    {
        $hachaku = $this->hachakuRepository->getDetail($hachakuCd);
        if(empty($hachaku)) {
            abort(404);
        }
        return view('master.hachaku.form', ['hachaku' => $hachaku, 'request' => $request]);
    }

    /**
     * Update the specified Hachaku in storage.
     *
     * @param  \App\Http\Requests\MeisyoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HachakuRequest $request, $hachakuCd)
    {
        $response = $this->hachakuRepository->update($request, $hachakuCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified Hachaku from storage.
     *
     * @param  int  $meisyoKbn
     * @param  int  $meisyoCd
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($hachakuCd)
    {
        $response = $this->hachakuRepository->delete($hachakuCd);
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

        $listData = $this->hachakuRepository->getListWithTotalCount($request);

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
        $listData = $this->hachakuRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();
        $config = require(app_path('Helpers/Excel/config/m_hachaku.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_hachaku.xlsx');
        $savePath = $directoryPath . '/m_hachaku.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }
}
