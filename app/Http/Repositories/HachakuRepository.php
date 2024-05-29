<?php 
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Models\MHachaku;

class HachakuRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb = $this->returnHachakuNinusiQueryBuilder();
       
        $list = $qb->filter($request);

        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['hachaku_cd']]),
        ];
    }

    public function getDetail($hachakuCd) 
    {
        $qb     = $this->returnHachakuNinusiQueryBuilder();

        $res    = $qb->where('m_hachaku.hachaku_cd', $hachakuCd)->first();
        
        return $res;
    }

    public function store($request) 
    {
        DB::beginTransaction();
        try {
            $hachaku = MHachaku::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $hachaku
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

    public function update($request, $hachakuCd) 
    {
        DB::beginTransaction();
        try {

            $res = MHachaku::where([
                ['hachaku_cd', '=', $hachakuCd]
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

    public function delete($hachakuCd) 
    {
        DB::beginTransaction();
        try {
            MHachaku::where([
                ['hachaku_cd', '=', $hachakuCd]
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

    public function returnHachakuNinusiQueryBuilder()
    {
        $qb =  MHachaku::select(
                    'm_hachaku.*', 
                    'n1.ninusi_ryaku_nm as atena_ninusi_nm', 
                    'n2.ninusi_ryaku_nm as ninusi_nm', 
                )
            ->leftJoin('m_ninusi as n1', 'n1.ninusi_cd', '=', 'm_hachaku.atena_ninusi_id')
            ->leftJoin('m_ninusi as n2', 'n2.ninusi_cd', '=', 'm_hachaku.ninusi_id');

        return $qb;
    }
}