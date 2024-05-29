<?php

namespace App\Http\Controllers\Seikyu;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Seikyu\SeikyuKakuteiRepository;
use App\Http\Requests\Seikyu\SeikyuListRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeikyuKakuteiController extends Controller
{
    protected $repository;
    public function __construct(SeikyuKakuteiRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $setting = require(app_path('Helpers/Grid/config/seikyu/t_seikyu_list.php'));
        return view('seikyu.kakutei.index', ['setting' => $setting, 'request' => $request]);
    }

    public function dataList(SeikyuListRequest $request)
    {
        $listData = $this->repository->getList($request);
        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->orderBy('ninusi_cd')
            ->get();

        return response()->json($data);
    }

    public function setFlag(SeikyuListRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->repository->setKakutei($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'message' => trans('messages.E0012')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['message' => trans('messages.updated2')], Response::HTTP_OK);
    }
}
