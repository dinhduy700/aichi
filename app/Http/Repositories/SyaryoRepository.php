<?php

namespace App\Http\Repositories;

use App\Models\MSyaryo;
use Illuminate\Http\Response;
use DB;

class SyaryoRepository
{
    public function getDetail($syaryoCd)
    {
        $syaryo = MSyaryo::where([
            ['syaryo_cd', '=', $syaryoCd],
        ])->select('m_syaryo.*', 'm_jyomuin.jyomuin_nm', 'm_yousya.yousya_ryaku_nm', 'm_bumon.bumon_nm', 'syasyu.meisyo_nm as syasyu_nm', 'rikuun.meisyo_nm as rikuun_nm')
            ->leftJoin('m_bumon', 'm_syaryo.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->leftJoin('m_jyomuin', 'm_syaryo.jyomuin_cd', '=', 'm_jyomuin.jyomuin_cd')
            ->leftJoin('m_yousya', 'm_syaryo.yousya_cd', '=', 'm_yousya.yousya_cd')
            ->leftJoin('m_meisyo as syasyu', function ($query) {
                $query->on('syasyu.meisyo_cd', '=', "m_syaryo.syasyu_cd");
                $query->where('syasyu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYASYU'));
            })
            ->leftJoin('m_meisyo as rikuun', function ($query) {
                $query->on('rikuun.meisyo_cd', '=', "m_syaryo.rikuun_cd");
                $query->where('rikuun.meisyo_kbn', '=', configParam('MEISYO_KBN_RIKUUN'));
            })
            ->first();
        return $syaryo;
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $syaryo = MSyaryo::create($data);
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $syaryo
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

    public function update($syaryoCd, $data)
    {
        DB::beginTransaction();
        try {
            $syaryo = MSyaryo::where([
                ['syaryo_cd', '=', $syaryoCd],
            ])->first();
            if ($syaryo) {
                $syaryo->update($data);
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

    public function delete($syaryoCd)
    {
        DB::beginTransaction();
        try {
            MSyaryo::where([
                ['syaryo_cd', '=', $syaryoCd],
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
        $list = MSyaryo::select('m_syaryo.*', 'm_jyomuin.jyomuin_nm', 'm_yousya.yousya_ryaku_nm as yousya_nm', 'm_bumon.bumon_nm', 'syasyu.meisyo_nm as syasyu_nm', 'rikuun.meisyo_nm as rikuun_nm')
            ->leftJoin('m_bumon', 'm_syaryo.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->leftJoin('m_jyomuin', 'm_syaryo.jyomuin_cd', '=', 'm_jyomuin.jyomuin_cd')
            ->leftJoin('m_yousya', 'm_syaryo.yousya_cd', '=', 'm_yousya.yousya_cd')
            ->leftJoin('m_meisyo as syasyu', function ($query) {
                $query->on('syasyu.meisyo_cd', '=', "m_syaryo.syasyu_cd");
                $query->where('syasyu.meisyo_kbn', '=', configParam('MEISYO_KBN_SYASYU'));
            })
            ->leftJoin('m_meisyo as rikuun', function ($query) {
                $query->on('rikuun.meisyo_cd', '=', "m_syaryo.rikuun_cd");
                $query->where('rikuun.meisyo_kbn', '=', configParam('MEISYO_KBN_RIKUUN'));
            })
            ->filter($request);
        $total = $list->count();

        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['syaryo_cd']]),
        ];
    }
}
