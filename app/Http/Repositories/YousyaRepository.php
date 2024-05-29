<?php 
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Models\MYousya;

class YousyaRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb     = $this->returnYousyaBumonQueryBuilder();

        $list   = $qb->filter($request);
       
        $total  = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['yousya_cd']]),
        ];
    }

    public function getDetail($yousyaCd) 
    {
        $qb     = $this->returnYousyaBumonQueryBuilder();

        $res    = $qb->where('m_yousya.yousya_cd', $yousyaCd)->first();
        
        return $res;
    }

    public function store($request) 
    {
        DB::beginTransaction();
        try {
            $yousya = MYousya::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $yousya
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

    public function update($request, $yousyaCd) 
    {
        DB::beginTransaction();
        try {

            $res = MYousya::where([
                ['yousya_cd', '=', $yousyaCd]
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

    public function delete($yousyaCd) 
    {
        DB::beginTransaction();
        try {
            MYousya::where([
                ['yousya_cd', '=', $yousyaCd]
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

    public function returnYousyaBumonQueryBuilder()
    {
        $qb = MYousya::select(
                    'm_yousya.*', 
                    'siharai.yousya_ryaku_nm as siharai_nm', 
                    'kaikake.yousya_ryaku_nm as kaikake_saki_nm', 
                    'm_bumon.bumon_nm'
                )
            ->leftJoin('m_bumon', 'm_yousya.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->leftJoin('m_yousya as siharai', 'siharai.yousya_cd', '=', 'm_yousya.siharai_cd')
            ->leftJoin('m_yousya as kaikake', 'kaikake.yousya_cd', '=', 'm_yousya.kaikake_saki_cd');

        return $qb;
    }
}