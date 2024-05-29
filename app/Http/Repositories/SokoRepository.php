<?php
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use DB;
use App\Models\MSoko;

class SokoRepository
{
    public function getTotalCount($request)
    {
        $list = $this->queryWithJoins($request);
        $total = $list->count();
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['bumon_cd'],['soko_cd']]),
        ];
    }

    public function getDetail($sokoCd, $bumonCd)
    {
        return $this->queryWithJoins()
            ->where('m_soko.soko_cd', $sokoCd)
            ->where('m_soko.bumon_cd', $bumonCd)
            ->first();
    }

    private function queryWithJoins($request = null)
    {
        $query = MSoko::select('m_soko.*', 'm_bumon.bumon_nm AS bumon_nm')
            ->leftJoin('m_bumon', 'm_bumon.bumon_cd', '=', 'm_soko.bumon_cd');

        if ($request) {
            $query->filter($request);
        }
        return $query;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $soko = MSoko::create($request->all());
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $soko
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

    public function update($request, $sokoCd, $bumonCd)
    {
        DB::beginTransaction();
        try {
            $soko = MSoko::where([
                ['soko_cd', '=', $sokoCd],
                ['bumon_cd', '=', $bumonCd]
            ])->first();

            if ($soko) {
                $soko->update($request->all());
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $soko
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

    public function delete($sokoCd, $bumonCd)
    {
        DB::beginTransaction();
        try {
            MSoko::where([
                ['soko_cd', '=', $sokoCd],
                ['bumon_cd', '=', $bumonCd]
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
