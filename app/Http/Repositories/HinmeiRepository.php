<?php
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use DB;
use App\Models\MHinmei;

class HinmeiRepository
{
    public function getTotalCount($request)
    {
        $list = $this->queryWithJoins($request);
        $total = $list->count();
        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function getDetail($hinmeiCd)
    {
        return $this->queryWithJoins()
            ->where('hinmei_cd', $hinmeiCd)
            ->first();
    }

    private function queryWithJoins($request = null)
    {
        $query = MHinmei::select('m_hinmei.*', 'm_hinmoku.hinmoku_nm as hinmoku_nm', 'm_bumon.bumon_nm as bumon_nm', 'm_ninusi.ninusi_ryaku_nm as ninusi_nm', 'm_meisyo.meisyo_nm as tani_nm')
            ->leftJoin('m_hinmoku', 'm_hinmoku.hinmoku_cd', '=', 'm_hinmei.hinmoku_cd')
            ->leftJoin('m_bumon', 'm_bumon.bumon_cd', '=', 'm_hinmei.bumon_cd')
            ->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', '=', 'm_hinmei.ninusi_id')
            ->leftJoin('m_meisyo', function($join) {
                $join->on('m_meisyo.meisyo_cd', '=', 'm_hinmei.tani_cd')
                    ->where('m_meisyo.meisyo_kbn', '=', 'tani');
            });

        if ($request) {
            $query->filter($request);
        }

        return $query;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $himei = MHinmei::create($request->all());
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $himei
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

    public function update($request, $hinmeiCd)
    {
        DB::beginTransaction();
        try {
            $himei = MHinmei::where([
                ['hinmei_cd', '=', $hinmeiCd]
            ])->first();

            if ($himei) {
                $himei->update($request->all());
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $himei
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

    public function delete($hinmeiCd)
    {
        DB::beginTransaction();
        try {
            MHinmei::where([
                ['hinmei_cd', '=', $hinmeiCd],
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