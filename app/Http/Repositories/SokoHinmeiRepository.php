<?php 
namespace App\Http\Repositories;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Models\MSokoHinmei;

class SokoHinmeiRepository 
{
    public function getListWithTotalCount($request)
    {
        $qb     = $this->returnSokoHinmeiQueryBuilder();
        $list   = $qb->filter($request);
       
        $total  = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['ninusi_cd'],['hinmei_cd']]),
        ];
    }

    public function getDetail($ninusiCd, $hinmeiCd) 
    {
        $qb     = $this->returnSokoHinmeiQueryBuilder();

        $res    = $qb->where('m_soko_hinmei.ninusi_cd', $ninusiCd)
                     ->where('m_soko_hinmei.hinmei_cd', $hinmeiCd)
                     ->first();
        
        return $res;
    }

    public function store($request) 
    {
        DB::beginTransaction();
        try {
            $res = MSokoHinmei::create($request->all()); 
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

    public function update($request, $ninusiCd, $hinmeiCd) 
    {
        DB::beginTransaction();
        try {
         
            $res = MSokoHinmei::where([
                ['ninusi_cd', '=', $ninusiCd],
                ['hinmei_cd', '=', $hinmeiCd]
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

    public function delete($ninusiCd, $hinmeiCd) 
    {
        DB::beginTransaction();
        try {
            MSokoHinmei::where([
                ['ninusi_cd', '=', $ninusiCd],
                ['hinmei_cd', '=', $hinmeiCd]
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

    public function returnSokoHinmeiQueryBuilder()
    {
        $qb = MSokoHinmei::select(
            'm_soko_hinmei.*', 
            'm_ninusi.ninusi_ryaku_nm as ninusi_nm',
            'case.meisyo_nm as case_nm',
            'case.meisyo_cd as case_cd',
            'bara.meisyo_cd as bara_tani',
            'bara.meisyo_nm as bara_tani_nm',
            'm_hinmei.hinmei_cd as seikyu_hinmei_cd',
            'm_hinmei.hinmei_nm as seikyu_hinmei_nm',
            'm_bumon.bumon_nm',
        )
            ->leftJoin('m_ninusi', 'm_soko_hinmei.ninusi_cd', '=', 'm_ninusi.ninusi_cd')

            ->leftJoin('m_meisyo as case', function ($j) {
                    $j->on("case.meisyo_cd", '=', "m_soko_hinmei.case_cd");
                    $j->where("case.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
            })
            ->leftJoin('m_meisyo as bara', function ($j) {
                    $j->on("bara.meisyo_cd", '=', "m_soko_hinmei.bara_tani");
                    $j->where("bara.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
            })

            ->leftJoin('m_hinmei', 'm_soko_hinmei.seikyu_hinmei_cd', '=', 'm_hinmei.hinmei_cd')
            
            ->leftJoin('m_bumon', 'm_soko_hinmei.bumon_cd', '=', 'm_bumon.bumon_cd')
            ;
           
        return $qb;
    }
}