<?php

namespace App\Http\Repositories\Picking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickingGurisutoRepository
{
    const EXP_PRINT_OTHER_CSV_HEADER   = '1';
    const EXP_PRINT_OTHER_SYABAN       = '2';
    const EXP_PRINT_OTHER_HACHAKU_CD   = '3';

    public function getExportPrintOtherOpts() 
    {
        return [
            self::EXP_PRINT_OTHER_CSV_HEADER => [
                'text' => 'CSV見出し出力（M）',
            ],
            self::EXP_PRINT_OTHER_SYABAN => [
                'text' => '車番で改貢',
            ],
            self::EXP_PRINT_OTHER_HACHAKU_CD => [
                'text' => '荷届け先で改貢',
            ],
        ];
    }

    public function qbExport($request, $route = null)
    {
        $qb = DB::table('t_nyusyuko_head')
                ->select([
                    't_nyusyuko_head.bumon_cd',
                    't_nyusyuko_head.syaban',
                    't_nyusyuko_head.jyomuin_cd',
                    't_nyusyuko_head.yousya_cd',
                    't_nyusyuko_head.hachaku_cd',
                    't_nyusyuko_head.todokesaki_nm',
                    't_nyusyuko_head.ninusi_cd',
                    't_nyusyuko_head.kisan_dt',
                ]);
   
        $qb->leftJoin('m_bumon', 't_nyusyuko_head.bumon_cd', '=', 'm_bumon.bumon_cd')
            ->addSelect('bumon_nm');

        $qb->join('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no')
            ->addSelect([
                        't_nyusyuko_meisai.hinmei_cd', 
                        't_nyusyuko_meisai.lot1',
                        't_nyusyuko_meisai.lot2',
                        't_nyusyuko_meisai.lot3',
                        't_nyusyuko_meisai.case_su',
                        't_nyusyuko_meisai.hasu',
                        't_nyusyuko_meisai.su',
                        't_nyusyuko_meisai.location', 
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
                        'm_soko_hinmei.bara_tani',
                    ]);
        
        switch ($route) {
            case 'picking.picking_gurisuto.exp.pdf';
            case 'picking.picking_gurisuto.exp.xls';
                $qb->leftJoin("m_meisyo AS m_meisyo_tani", function ($j) {
                    $j->on("t_nyusyuko_meisai.tani_cd", '=', "m_meisyo_tani.meisyo_cd");
                    $j->where("m_meisyo_tani.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
                })->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");
                break;
            case 'picking.picking_gurisuto.exp.csv':
                $qb->leftJoin('m_jyomuin', 't_nyusyuko_head.jyomuin_cd', '=', 'm_jyomuin.jyomuin_cd')
                    ->addSelect('jyomuin_nm');
    
                $qb->leftJoin('m_yousya', 't_nyusyuko_head.yousya_cd', '=', 'm_yousya.yousya_cd')
                    ->addSelect('yousya_ryaku_nm');

                $qb->leftJoin("m_meisyo AS m_meisyo_case", function ($j) {
                    $j->on("m_soko_hinmei.case_cd", '=', "m_meisyo_case.meisyo_cd");
                    $j->where("m_meisyo_case.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
                })->addSelect("m_meisyo_case.meisyo_nm AS case_nm");
        
                $qb->leftJoin("m_meisyo AS m_meisyo_bara_tani", function ($j) {
                    $j->on("m_soko_hinmei.bara_tani", '=', "m_meisyo_bara_tani.meisyo_cd");
                    $j->where("m_meisyo_bara_tani.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
                })->addSelect("m_meisyo_bara_tani.meisyo_nm AS bara_tani_nm");
                break;
        }
        
         // Filters
        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd'      => 't_nyusyuko_head.bumon_cd',
                'kisan_dt'      => 't_nyusyuko_head.kisan_dt',
                'hachaku_cd'    => 't_nyusyuko_head.hachaku_cd',
                'syaban'        => 't_nyusyuko_head.syaban',
                'jyomuin_cd'    => 't_nyusyuko_head.jyomuin_cd',
                'yousya_cd'     => 't_nyusyuko_head.yousya_cd',
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

        $isSyaban = in_array(
            self::EXP_PRINT_OTHER_SYABAN,
            data_get($request, 'exp.print_other', [])
        );

        $isHachaku = in_array(
            self::EXP_PRINT_OTHER_HACHAKU_CD,
            data_get($request, 'exp.print_other', [])
        );

        if ($isSyaban) {
            $qb->orderBy('t_nyusyuko_head.syaban');
        }

        if ($isHachaku) {
            $qb->orderBy('t_nyusyuko_head.hachaku_cd');
        }

        $qb->orderBy('t_nyusyuko_head.bumon_cd');
        $qb->orderBy('t_nyusyuko_head.kisan_dt');

        return $qb;
    }
}