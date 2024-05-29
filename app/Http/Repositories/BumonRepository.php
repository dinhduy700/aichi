<?php
namespace App\Http\Repositories;
use Illuminate\Http\Response;
use DB;
use App\Models\MBumon;

class BumonRepository {
    public function getTotalCount($request)
    {
        $list = MBumon::select()->filter($request);
        $total = $list->count();
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['bumon_cd']]),
        ];
    }

    public function getDetail($bumonCd) {
        $bumon = MBumon::where([
            ['bumon_cd', '=', $bumonCd]
        ])->first();
        return $bumon;
    }

    public function store ($request) 
    {
        DB::beginTransaction();
        try {
            $bumon = MBumon::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $bumon
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

    public function update ($request, $bumonCd) 
    {
        DB::beginTransaction();
        try {
            $bumon = MBumon::where([
                ['bumon_cd', '=', $bumonCd]
            ])->first(); 
            
            if ($bumon) {
                $bumon->update($request->all());
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $bumon
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

    public function delete ($bumonCd)
    {
        DB::beginTransaction();
        try {
            MBumon::where([
                ['bumon_cd', '=', $bumonCd], 
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