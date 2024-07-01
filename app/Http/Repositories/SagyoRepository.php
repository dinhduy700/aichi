<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\TUriage;

class SagyoRepository
{
    const EXP_PRINT_OTHER_GROUP_BY_SYABAN       = '1';
    const EXP_PRINT_OTHER_MOBILE_TEL            = '2';
    const EXP_PRINT_OTHER_YOUSYA_CD_RYAKU_NM    = '3';
    const EXP_PRINT_OTHER_CSV_HEADER            = '4';
    const EXP_PRINT_OTHER_YOSYA_TYUKEI_KIN      = '5';

    const EXP_TYOHYO_KBN_ALL                    = '1';
    const EXP_TYOHYO_KBN_JYOMUIN_CD             = '2';
    const EXP_TYOHYO_KBN_YOUSYA_CD              = '3';

    const EXP_PRINT_INJI_SYUKA_DT               = '1';
    const EXP_PRINT_INJI_HAITATU_DT             = '2';
    const EXP_PRINT_INJI_NIPOU_DT               = '3';

    public function getExportInjiGroupOpts()
    {
        $dateFrom   = Carbon::parse(request('exp.dt_from'));
        $dateTo     = Carbon::parse(request('exp.dt_to'));

        return [
            self::EXP_PRINT_INJI_SYUKA_DT => [
                'text' => '集荷日',
                'field' => 'syuka_dt',
                'orderBy' => ['syuka_dt'],
                'groupBy' => 'syuka_dt',
                'where' => function (&$qb, $table = 't_uriage') use($dateFrom, $dateTo){
                    if (!empty(request('exp.dt_from'))) {
                        $qb->where("{$table}.syuka_dt", '>=', $dateFrom);
                    }
                    if (!empty(request('exp.dt_to'))) {
                        $qb->where("{$table}.syuka_dt", '<=', $dateTo);
                    }
                }
            ],
            self::EXP_PRINT_INJI_HAITATU_DT => [
                'text' => '配達日',
                'field' => 'haitatu_dt',
                'orderBy' => ['haitatu_dt'],
                'groupBy' => 'haitatu_dt',
                'where' => function (&$qb, $table = 't_uriage') use($dateFrom, $dateTo){
                    if (!empty(request('exp.dt_from'))) {
                        $qb->where("{$table}.haitatu_dt", '>=', $dateFrom);
                    }
                    if (!empty(request('exp.dt_to'))) {
                        $qb->where("{$table}.haitatu_dt", '<=', $dateTo);
                    }
                }
            ],
            self::EXP_PRINT_INJI_NIPOU_DT => [
                'text' => '日報日',
                'field' => 'nipou_dt',
                'orderBy' => ['nipou_dt'],
                'groupBy' => 'nipou_dt',
                'where' => function (&$qb, $table = 't_uriage') use($dateFrom, $dateTo){
                    if (!empty(request('exp.dt_from'))) {
                        $qb->where("{$table}.nipou_dt", '>=', $dateFrom);
                    }
                    if (!empty(request('exp.dt_to'))) {
                        $qb->where("{$table}.nipou_dt", '<=', $dateTo);
                    }
                }
            ],
        ];
    }

    public function getExportTyohyokbnOpts()
    {
        return [
            self::EXP_TYOHYO_KBN_ALL => [
                'text'                  => '全出力',
            ],
            self::EXP_TYOHYO_KBN_JYOMUIN_CD => [
                'text'                  => '自社　',
                'fieldConcat'           => 'jyomuin_cd_nm',
                'fieldsUserPgFunc'      => ['choice1_char', 'choice2_char', 'choice3_char', 'choice4_char'],
                'groupBy'               => 'jyomuin_cd',
                'where'                 => function (&$qb, $table = 't_uriage') {},
                'orderBy'               => ['jyomuin_cd'],
            ],
            self::EXP_TYOHYO_KBN_YOUSYA_CD => [
                'text'                  => '庸車',
                'fieldConcat'           => 'yousya_cd_ryaku_nm',
                'fieldsUserPgFunc'      => ['choice5_char', 'choice6_char', 'choice7_char', 'choice8_char'],
                'groupBy'               => 'yousya_cd',
                'where'                 => function (&$qb, $table = 't_uriage') { },
                'orderBy'               => ['yousya_cd'],
            ],
        ];
    }

    public function getExportPrintOtherOpts()
    {
        return [
            self::EXP_PRINT_OTHER_GROUP_BY_SYABAN => [
                'text' => '車両別改頁'
            ],
            self::EXP_PRINT_OTHER_MOBILE_TEL => [
                'text' => '携帯番号出力',
            ],
            self::EXP_PRINT_OTHER_CSV_HEADER => [
                'text' => 'csv見出し出力',
            ],
            self::EXP_PRINT_OTHER_YOSYA_TYUKEI_KIN => [
                'text' => '庸車料出力',
            ],
        ];
    }

    public function qbExport($request)
    {
        $tUriage = new TUriage();
        $table = $tUriage->getTable();
        $qb = $tUriage->filter($request);// $qb = new Builder();
        $qb->select("{$table}.*");
        $qb->addSelect([
            DB::raw("{$table}.hatuti_hachaku_nm as hatuti_nm"),
            DB::raw("CASE WHEN {$table}.syuka_tm IS NOT NULL THEN CONCAT({$table}.syuka_tm, '指') ELSE '' END as syuka_tm_formatted"),
            DB::raw("CASE WHEN {$table}.jikoku IS NOT NULL THEN CONCAT({$table}.jikoku, '指') ELSE '' END as jikoku_formatted"),
            DB::raw("CONCAT(m_meisyo_rikuun.meisyo_nm, ' ', car_number_syubetu, car_number_kana, car_number) as card_nm"),
        ]);
        // 部門マスタ
        $qb->joinMBumon()->addSelect("bumon_nm");
        // 荷主マスタ
        $qb->joinMNinusi()->addSelect(["m_ninusi.ninusi_ryaku_nm", DB::raw("CONCAT(m_ninusi.ninusi_cd, ' ', m_ninusi.ninusi_ryaku_nm) as ninusi_cd_ryaku_nm")]);
        
        // 名称マスタ
        $qb->joinMMeisyoJyutyu()->addSelect("m_meisyo_jyutyu.meisyo_nm AS jyutyu_kbn_nm");
        $qb->joinMMeisyoTani()->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");

        // 庸車先マスタ
        $qb->joinMYousya()->addSelect(["yousya1_nm", DB::raw("CONCAT(m_yousya.yousya_cd, ' ', m_yousya.yousya_ryaku_nm) as yousya_cd_ryaku_nm"), "m_yousya.yousya_ryaku_nm"]);
        // 乗務員マスタ
        $qb->joinMJyomuin()->addSelect(["jyomuin_nm", "mobile_tel", DB::raw("CONCAT(m_jyomuin.jyomuin_cd, ' ', m_jyomuin.jyomuin_nm) as jyomuin_cd_nm")]);
        $qb->joinMSyaryo();

        $qb->leftJoin("m_meisyo AS m_meisyo_rikuun", function ($j) {
            $j->on("m_meisyo_rikuun.meisyo_cd", '=', "m_syaryo.rikuun_cd");
            $j->where("m_meisyo_rikuun.meisyo_kbn", '=', configParam('MEISYO_KBN_RIKUUN'));
        });

        return $qb;
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);

        $qb->whereNotNull('t_uriage.syaban');

        $orderByInjiGroup = data_get($this->getExportInjiGroupOpts(), @$cloneReq->inji_group . ".orderBy", ['syuka_dt']);
        $qb->orderBy(...$orderByInjiGroup);

        $orderByTyohyokbn = data_get($this->getExportTyohyokbnOpts(), @$cloneReq->tyohyokbn . ".orderBy", ['jyomuin_cd']);
        $qb->orderBy(...$orderByTyohyokbn);
        $func = data_get($this->getExportTyohyokbnOpts(), @$cloneReq->tyohyokbn . ".where");
        if ($func instanceof \Closure) {
            $qb->where($func($qb));
        }

        $qb->orderBy('uriage_den_no');

        // 出力方法
        $opts = $this->getExportInjiGroupOpts();
        $func = $opts[$cloneReq->inji_group]['where'];
        $qb->where($func($qb));

        return $qb;
    }

    public function getBumonNm($bumonCd)
    {
        $res = DB::table('m_bumon')
            ->where('bumon_cd', $bumonCd)
            ->value('bumon_nm');

        return $res;
    }
}
