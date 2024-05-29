<?php

namespace App\Http\Repositories\Tanaorosi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TanaorosiRepository
{
    const EXP_PRINT_OTHER_CSV_HEADER = '1';

    public function getExportPrintOtherOpts()
    {
        return [
            self::EXP_PRINT_OTHER_CSV_HEADER => [
                'text' => 'CSV見出し出力（M）'
            ],
        ];
    }

    public function qbExport($request, $routeNm)
    {
        $qb = DB::table('t_zaiko')->select([
            't_zaiko.bumon_cd',
            't_zaiko.soko_cd',
            't_zaiko.location',
            't_zaiko.hinmei_cd',
            't_zaiko.lot1',
            't_zaiko.lot2',
            't_zaiko.lot3',
            't_zaiko.case_su',
            't_zaiko.hasu',
            't_zaiko.su',
            't_zaiko.ninusi_cd',
            DB::raw('now() as current_date'),
        ]);

        $qb->leftJoin('m_bumon', 't_zaiko.bumon_cd', '=', 'm_bumon.bumon_cd')->addSelect(['m_bumon.bumon_nm']);

        $qb->leftJoin('m_ninusi', 't_zaiko.ninusi_cd', '=', 'm_ninusi.ninusi_cd')->addSelect(['m_ninusi.ninusi1_nm']);

        $qb->leftJoin('m_soko_hinmei', function ($j) {
            $j->on('t_zaiko.hinmei_cd', 'm_soko_hinmei.hinmei_cd')
                ->on('t_zaiko.ninusi_cd', 'm_soko_hinmei.ninusi_cd');
        })->addSelect([
                        'm_soko_hinmei.hinmei_nm',
                        'm_soko_hinmei.kikaku',
                        'm_soko_hinmei.irisu',
                        'm_soko_hinmei.case_cd',
                        'm_soko_hinmei.bara_tani',
                    ]);

        $qb->leftJoin('m_soko', function ($j) {
            $j->on('t_zaiko.bumon_cd', 'm_soko.bumon_cd');
            $j->on('t_zaiko.soko_cd', 'm_soko.soko_cd');
        })->addSelect(['m_soko.soko_nm']);

        if ($routeNm == 'tanaorosi.exp.csv') {
            $qb->leftJoin('m_meisyo as m_meisyo_case', function ($j) {
                $j->on("m_meisyo_case.meisyo_cd", '=', "m_soko_hinmei.case_cd");
                $j->where("m_meisyo_case.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
            })->addSelect('m_meisyo_case.meisyo_nm AS case_nm');
    
            $qb->leftJoin('m_meisyo as m_meisyo_bara', function ($j) {
                    $j->on("m_meisyo_bara.meisyo_cd", '=', "m_soko_hinmei.bara_tani");
                    $j->where("m_meisyo_bara.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
            })->addSelect('m_meisyo_bara.meisyo_nm AS bara_tani_nm');
        }


        // Filters
        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd'      => 't_zaiko.bumon_cd',
                'ninusi_cd'     => 't_zaiko.ninusi_cd',
                'location'      => 't_zaiko.location',
                'soko_cd'       => 't_zaiko.soko_cd',
                'hinmei_cd'     => 't_zaiko.hinmei_cd',
            ];
   
            foreach ($fromTo as $reqKey => $field) {
                if (is_null($value)) continue;
                if ($key == "{$reqKey}_from") $qb->where($field, '>=', $value);
                if ($key == "{$reqKey}_to") $qb->where($field, '<=', $value);
            }
        }
        
        return $qb;
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);

        $qb->orderBy('t_zaiko.bumon_cd');
        $qb->orderBy('t_zaiko.soko_cd');
        $qb->orderBy('t_zaiko.ninusi_cd');
        $qb->orderBy('t_zaiko.hinmei_cd');

        return $qb;
    }
}
