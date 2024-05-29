<?php

namespace App\Http\Repositories\Picking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoryoRepository
{
    const EXP_PRINT_OTHER_CSV_DISPLAY_ZAIKO = '1';
    const EXP_PRINT_OTHER_CSV_HEADER        = '2';

    public function getExportPrintOtherOpts() 
    {
        return [
            self::EXP_PRINT_OTHER_CSV_DISPLAY_ZAIKO => [
                'text' => '在庫数印刷あり'
            ],
            self::EXP_PRINT_OTHER_CSV_HEADER => [
                'text' => 'CSV見出し出力（M）',
            ],
        ];
    }

    public function qbExport($request, $route = null)
    {
        $displayZaiko = in_array(
            self::EXP_PRINT_OTHER_CSV_DISPLAY_ZAIKO,
            data_get($request, 'print_other', [])
        );

        $qb = DB::table('t_nyusyuko_head')
                ->select([
                    't_nyusyuko_head.bumon_cd',
                    't_nyusyuko_head.ninusi_cd',
                    't_nyusyuko_head.kisan_dt',
                ]);
   
        $qb->leftJoin('m_bumon', 't_nyusyuko_head.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->addSelect('bumon_nm');

        $qb->join('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no')
            ->addSelect(['t_nyusyuko_meisai.hinmei_cd', 
                        't_nyusyuko_meisai.location', 
                        't_nyusyuko_meisai.tani_cd', 
                        't_nyusyuko_meisai.hinmei_cd',
                        't_nyusyuko_meisai.lot1',
                        't_nyusyuko_meisai.lot2',
                        't_nyusyuko_meisai.lot3',
                        't_nyusyuko_meisai.case_su',
                        't_nyusyuko_meisai.hasu',
                        't_nyusyuko_meisai.su',
                    ]);

        $qb->leftJoin('m_ninusi', 't_nyusyuko_head.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->addSelect('ninusi1_nm');

        $qb->leftJoin('m_soko_hinmei', function ($j) {
            $j->on('t_nyusyuko_head.ninusi_cd', '=', 'm_soko_hinmei.ninusi_cd');
            $j->on('t_nyusyuko_meisai.hinmei_cd', '=', 'm_soko_hinmei.hinmei_cd');
        })->addSelect(['m_soko_hinmei.hinmei_nm', 
                        'm_soko_hinmei.kikaku',
                        'm_soko_hinmei.irisu',
                        'm_soko_hinmei.case_cd',
                    ]);
    
        $qb->leftJoin("m_meisyo AS m_meisyo_tani", function ($j) {
            $j->on("t_nyusyuko_meisai.tani_cd", '=', "m_meisyo_tani.meisyo_cd");
            $j->where("m_meisyo_tani.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
        })->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");

        //在庫数印刷あり
        if ($displayZaiko) {
            $qb->leftJoin("t_zaiko_nyusyuko_meisai", function ($j) {
                $j->on("t_zaiko_nyusyuko_meisai.nyusyuko_den_no", '=', "t_nyusyuko_meisai.nyusyuko_den_no");
                $j->on("t_zaiko_nyusyuko_meisai.nyusyuko_den_meisai_no", '=', "t_nyusyuko_meisai.nyusyuko_den_meisai_no");
            });

            $qb->leftJoin('t_zaiko', 't_zaiko.seq_no', '=', 't_zaiko_nyusyuko_meisai.zaiko_seq_no')
                ->addSelect([
                    't_zaiko.case_su as t_zaiko__case_su', 
                    't_zaiko.hasu as t_zaiko__hasu', 
                    't_zaiko.su as t_zaiko__su', 
                ]);
        }

        if ($route == 'picking.soryo.exp.csv' 
        || ($route == 'picking.soryo.exp.pdf' && $displayZaiko)
        || ($route == 'picking.soryo.exp.xls' && $displayZaiko)
        ) {
            $qb->leftJoin("m_meisyo AS m_meisyo_case", function ($j) {
                $j->on("m_soko_hinmei.case_cd", '=', "m_meisyo_case.meisyo_cd");
                $j->where("m_meisyo_case.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
            })->addSelect("m_meisyo_case.meisyo_nm AS case_nm");
        }

        switch ($route) {
            case 'picking.soryo.exp.csv':
                $qb->leftJoin('m_soko', 't_nyusyuko_meisai.soko_cd', '=', 'm_soko.soko_cd')
                    ->addSelect(['m_soko.soko_cd', 'm_soko.soko_nm']);
                break;
        }

         // Filters
         foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd' => 't_nyusyuko_head.bumon_cd',
                'ninusi_cd' => 't_nyusyuko_head.ninusi_cd',
                'kisan_dt' => 't_nyusyuko_head.kisan_dt',
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

        $qb->where('t_nyusyuko_head.nyusyuko_kbn', 2);

        $qb->orderBy('t_nyusyuko_head.bumon_cd');

        return $qb;
    }
}