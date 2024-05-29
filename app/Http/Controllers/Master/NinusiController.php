<?php

namespace App\Http\Controllers\Master;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\NinusiRepository;
use App\Helpers\XlsExportMst;
use App\Http\Requests\NinusiRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\MNinusi;
use DB;

class NinusiController extends Controller
{

    protected $ninusiRepository;

    public function __construct(
        NinusiRepository $ninusiRepository
    ) {
        $this->ninusiRepository = $ninusiRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_ninusi.php'));
        $setting = removeWidthSizeGridConf($setting);
        return view('master.ninusi.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('master.ninusi.form',['request'=> $request]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NinusiRequest $request)
    {
        $response = $this->ninusiRepository->store($request->all());
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

  /**
     * Show the form for editing the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ninusiCd
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $ninusiCd)
    {
        $ninusi = $this->ninusiRepository->getDetail($ninusiCd);
        if (empty($ninusi)) {
            abort(404);
        }
        return view('master.ninusi.form', ['ninusi' => $ninusi, 'request' => $request]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\NinusiRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NinusiRequest $request,  $ninusiCd)
    {
        $response = $this->ninusiRepository->update($ninusiCd, $request->all());
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $ninusiCd
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($ninusiCd)
    {
        $response = $this->ninusiRepository->delete($ninusiCd);
        if ($response['status'] == 200) {
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

        $listData = $this->ninusiRepository->getListWithTotalCount($request);

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
        $listData = $this->ninusiRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();

        $config = require(app_path('Helpers/Excel/config/m_ninusi.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_ninusi.xlsx');
        $savePath = $directoryPath . '/m_ninusi.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }

}
