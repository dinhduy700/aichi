<?php

namespace App\Http\Controllers\Master;

use App\Helpers\XlsExportMst;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{

    protected $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/m_user.php'));
        return view('master.user.index', ['setting' => $setting, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('master.user.form', ['request' => $request]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $response = $this->userRepository->store($request->all());
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
     * @param  string  $userCd
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $userCd)
    {
        $user = $this->userRepository->getDetail($userCd);
        if (empty($user)) {
            abort(404);
        }
        return view('master.user.form', ['user' => $user, 'request' => $request]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $userCd)
    {
        if ($request->input('passwd') == configParam('USER_PASSWD_EDIT')) {
            $request->offsetUnset('passwd');
        }
        $response = $this->userRepository->update($userCd, $request->all());
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
    public function destroy($userCd)
    {
        $response = $this->userRepository->delete($userCd);
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

        $listData = $this->userRepository->getListWithTotalCount($request);

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
        $listData = $this->userRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();

        $config = require(app_path('Helpers/Excel/config/m_user.php'));

        // Specify the directory path
        $directoryPath = storage_path('app/download');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $templatePath = app_path('Helpers/Excel/template/m_user.xlsx');
        $savePath = $directoryPath . '/m_user.xlsx';
        $exporter->export($templatePath, $config, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }
}
