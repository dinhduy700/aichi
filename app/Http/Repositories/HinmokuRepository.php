<?php 
namespace App\Http\Repositories;

use App\Models\MHinmoku;
use Illuminate\Http\Response;
use DB;

class HinmokuRepository {
    public function getTotalCount($request)
    {
        $list = MHinmoku::select()->filter($request);
        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['hinmoku_cd']]),
        ];
    }

    public function getDetail($hinmokuCd) {
        $hinmoku = MHinmoku::where([
            ['hinmoku_cd', '=', $hinmokuCd]
        ])->first();
        return $hinmoku;
    }

    public function store ($request) 
    {
        DB::beginTransaction();
        try {
            $hinmoku = MHinmoku::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $hinmoku
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

    public function update ($request, $hinmokuCd) 
    {
        DB::beginTransaction();
        try {
            $hinmoku = MHinmoku::where([
                ['hinmoku_cd', '=', $hinmokuCd]
            ])->first(); 
            
            if ($hinmoku) {
                $hinmoku->update($request->all());
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $hinmoku
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

    public function delete ($hinmokuCd)
    {
        DB::beginTransaction();
        try {
            MHinmoku::where([
                ['hinmoku_cd', '=', $hinmokuCd], 
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