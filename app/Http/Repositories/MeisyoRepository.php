<?php 
namespace App\Http\Repositories;

use App\Models\MMeisyo;
use Illuminate\Http\Response;
use DB;

class MeisyoRepository 
{
    public function getListWithTotalCount($request)
    {
        $list = MMeisyo::select('*')->filter($request);
        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function getDetail($meisyoKbn, $meisyoCd) {
        $meisyo = MMeisyo::where([
            ['meisyo_kbn', '=', $meisyoKbn],
            ['meisyo_cd', '=', $meisyoCd]
        ])->first();
        return $meisyo;
    }

    public function store($request) {
        DB::beginTransaction();
        try {
            $meisyo = MMeisyo::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $meisyo
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

    public function update($request, $meisyoKbn, $meisyoCd) {
        DB::beginTransaction();
        try {
            $meisyo = MMeisyo::where([
                ['meisyo_kbn', '=', $meisyoKbn],
                ['meisyo_cd', '=', $meisyoCd]
            ])->first();

            if ($meisyo) {
                $meisyo->update($request->all());
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

    public function delete($meisyoKbn, $meisyoCd) {
        DB::beginTransaction();
        try {
            MMeisyo::where([
                ['meisyo_kbn', '=', $meisyoKbn], 
                ['meisyo_cd', '=', $meisyoCd]
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