<?php

namespace App\Http\Repositories;

use App\Models\MNinusi;
use Illuminate\Http\Response;
use DB;

class NinusiRepository
{
    public function getDetail($ninusiCd)
    {
        $ninusi = MNinusi::where([
            ['m_ninusi.ninusi_cd', '=', $ninusiCd],
        ])->select('m_ninusi.*', 'seikyu.ninusi_ryaku_nm as seikyu_nm', 'urikake_saki.ninusi_ryaku_nm as urikake_saki_nm', 'm_bumon.bumon_nm')
            ->leftJoin('m_bumon', 'm_ninusi.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->leftJoin('m_ninusi as seikyu', 'seikyu.ninusi_cd', '=', 'm_ninusi.seikyu_cd')
            ->leftJoin('m_ninusi as urikake_saki', 'urikake_saki.ninusi_cd', '=', 'm_ninusi.urikake_saki_cd')
            ->leftJoin('m_bumon AS m_bumon_soko', 'm_ninusi.soko_bumon_cd', '=', 'm_bumon_soko.bumon_cd')
            ->addSelect(['m_bumon_soko.bumon_nm AS soko_bumon_nm'])
            ->leftJoin('m_ninusi as m_ninusi_soko', 'm_ninusi.soko_seikyu_cd', '=', 'm_ninusi_soko.ninusi_cd')
            ->addSelect(['m_ninusi_soko.ninusi_ryaku_nm AS soko_seikyu_nm'])
            ->first();
        return $ninusi;
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $ninusi = MNinusi::create($data);
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $ninusi
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

    public function update($ninusiCd, $data)
    {
        DB::beginTransaction();
        try {
            $ninusi = MNinusi::where([
                ['ninusi_cd', '=', $ninusiCd],
            ])->first();
            if ($ninusi) {
                $ninusi->update($data);
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

    public function delete($ninusiCd)
    {
        DB::beginTransaction();
        try {
            MNinusi::where([
                ['ninusi_cd', '=', $ninusiCd],
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

    public function getListWithTotalCount($request)
    {
        $list = MNinusi::select('m_ninusi.*')
            ->leftJoin('m_bumon', 'm_ninusi.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->leftJoin('m_ninusi as seikyu', 'seikyu.ninusi_cd', '=', 'm_ninusi.seikyu_cd')
            ->leftJoin('m_ninusi as urikake_saki', 'urikake_saki.ninusi_cd', '=', 'm_ninusi.urikake_saki_cd')
            ->addSelect('m_bumon.bumon_nm', 'seikyu.ninusi_ryaku_nm as seikyu_nm', 'urikake_saki.ninusi_ryaku_nm as urikake_saki_nm')
            ->filter($request);
        $total = $list->count();

        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['m_ninusi.ninusi_cd']]),
        ];
    }
}
