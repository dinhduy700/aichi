<?php 
namespace App\Http\Repositories;

use App\Models\MJyomuin;
use Illuminate\Http\Response;
use DB;

class JyomuinRepository {
    public function getTotalCount($request)
    {
        $list = MJyomuin::select('m_jyomuin.*', 'm_bumon.bumon_nm as bumon_nm')->filter($request)
            ->leftJoin('m_bumon', 'm_bumon.bumon_cd', '=', 'm_jyomuin.bumon_cd');
        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['m_jyomuin.jyomuin_cd']]),
        ];
    }

    public function getDetail($jyomuinCd) {
        $jyomuin = MJyomuin::where([
            ['jyomuin_cd', '=', $jyomuinCd]
        ])->select('m_jyomuin.*', 'm_bumon.bumon_nm as bumon_nm')
            ->leftJoin('m_bumon', 'm_bumon.bumon_cd', '=', 'm_jyomuin.bumon_cd')
            ->first();
        return $jyomuin;
    }

    public function store ($request) 
    {
        DB::beginTransaction();
        try {
            $jyomuin = MJyomuin::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $jyomuin
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

    public function update ($request, $jyomuinCd) 
    {
        DB::beginTransaction();
        try {
            $jyomuin = MJyomuin::where([
                ['jyomuin_cd', '=', $jyomuinCd]
            ])->first(); 
            
            if ($jyomuin) {
                $jyomuin->update($request->all());
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $jyomuin
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

    public function delete ($jyomuinCd)
    {
        DB::beginTransaction();
        try {
            MJyomuin::where([
                ['jyomuin_cd', '=', $jyomuinCd], 
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