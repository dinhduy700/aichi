<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use App\Http\Repositories\YousyaRepository;
use App\Helpers\XlsExportMst;
use App\Http\Requests\YousyaRequest;

class YousyaController extends Controller
{
    protected $yousyaRepository;

    public function __construct(
        YousyaRepository $yousyaRepository
    ) {
        $this->yousyaRepository = $yousyaRepository;
    }

    /**
     * Display the Yousya index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_yousya.php'));
        return view('master.yousya.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Display the form for creating a new Yousya.
     *
     * @param  \App\Http\Requests\HachakuRequest  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_yousya.php'));
        return view('master.yousya.form', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Store a newly created yousya in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(YousyaRequest $request)
    {
        $response = $this->yousyaRepository->store($request);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.inserted'));
        }
        return response()->json($response);
    }

    /**
     * Display the specified Yousya.
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
     * @param  int  $yousyaCd
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $yousyaCd)
    {
        $yousya = $this->yousyaRepository->getDetail($yousyaCd);
       
        if(empty($yousya)) {
            abort(404);
        }
        return view('master.yousya.form', ['yousya' => $yousya, 'request' => $request]);
    }

    /**
     * Update the specified Yousya in storage.
     *
     * @param  \App\Http\Requests\YousyaRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(YousyaRequest $request, $yousyaCd)
    {
        $response = $this->yousyaRepository->update($request, $yousyaCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    /**
     * Remove the specified Yousya from storage.
     *
     * @param  int  $meisyoKbn
     * @param  int  $meisyoCd
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($yousyaCd)
    {
        $response = $this->yousyaRepository->delete($yousyaCd);
        if($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }

    /**
     * Get a list with total count of Yousya data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataList(Request $request) 
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->yousyaRepository->getListWithTotalCount($request);
        
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
        $listData = $this->yousyaRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();
        $config = require(app_path('Helpers/Excel/config/m_yousya.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_yousya.xlsx');
        $savePath = $directoryPath . '/m_yousya.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }
}