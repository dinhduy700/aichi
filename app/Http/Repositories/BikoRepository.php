<?php 
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Models\MBiko;

class BikoRepository 
{
    public function getListWithTotalCount($request)
    {
        $list = MBiko::select('*')->filter($request);

        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['biko_cd']]),
        ];
    }

    public function getDetail($bikoCd) 
    {
        $res = MBiko::where([
            ['biko_cd', '=', $bikoCd]
        ])->first();
        
        return $res;
    }

    public function store($request) 
    {
        DB::beginTransaction();
        try {
            $res = MBiko::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $res
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }

    public function update($request, $bikoCd) 
    {
        DB::beginTransaction();
        try {

            $res = MBiko::where([
                ['biko_cd', '=', $bikoCd]
            ])->first();

            if ($res) {
                $res->update($request->all());
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => null
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }

    public function delete($bikoCd) 
    {
        DB::beginTransaction();
        try {
            MBiko::where([
                ['biko_cd', '=', $bikoCd]
            ])->delete();
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => null
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }
}