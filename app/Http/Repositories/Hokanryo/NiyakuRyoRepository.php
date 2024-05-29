<?php


namespace App\Http\Repositories\Hokanryo;


use App\Models\TZaikoHokanryo;
use Illuminate\Http\Request;

class NiyakuRyoRepository
{
    const EXP_PRINT_CSV_HEADER = '1';
    const EXP_PRINT_RYO_LT_0 = '2';
    const EXP_HIDE_EXP_DT = '3';

    const EXP_KBN_SYOHIN = '1';
    const EXP_KBN_SEIKYU = '2';

    public function getOptionOpts()
    {
        return [
            self::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力（M）'],
            self::EXP_PRINT_RYO_LT_0 => [
                'text' => '入出庫料・保管料が0の商品は印字しない',
                'where' => function(&$qb) {
                    //保管料 + 荷役料　入庫料 + 荷役料　出庫料
                    $qb->whereRaw("(hokan_kin + nyuko_kin + syuko_kin) > 0");
                }
            ],
            self::EXP_HIDE_EXP_DT => ['text' => '発行日を印字しない'],
        ];
    }

    public function getPrintOpts()
    {
        return [
            self::EXP_KBN_SYOHIN => [
                'text' => '商品単位',
                'group_by' => ['ninusi_cd', 'seikyu_sime_dt', 'hinmei_cd'],
            ],
            self::EXP_KBN_SEIKYU => [
                'text' => '請求品名単位',
                'group_by' => ['ninusi_cd', 'seikyu_sime_dt'],
            ],
        ];
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        // handle request.bumon_cd in qbExport
        $qb = $this->qbExport($cloneReq, $routeNm);

        foreach ($cloneReq->all() as $key => $value) {
            $fromTo = ['ninusi_cd'];
            foreach ($fromTo as $field) {
                if (is_null($value)) continue;
                if ($key == "{$field}_from") $qb->where("t_zaiko_hokanryo.{$field}", '>=', $value);
                if ($key == "{$field}_to") $qb->where("t_zaiko_hokanryo.{$field}", '<=', $value);
            }
        }

        $seikyuSimeDt = $cloneReq->get('seikyu_sime_yymm') . '/' . $cloneReq->get('seikyu_sime_dd');
        $format = 'y/m/d';
        $seikyuSimeDt = \DateTime::createFromFormat('!' . $format, $seikyuSimeDt);
        $qb->where('seikyu_sime_dt', $seikyuSimeDt);

        if (in_array(self::EXP_PRINT_RYO_LT_0, $cloneReq->get('option', []))) {
            $func = data_get(self::getOptionOpts(), self::EXP_PRINT_RYO_LT_0.'.where');
            $func($qb);
        }

        $orderBy = data_get(self::getPrintOpts(), $cloneReq->get('export_kbn', self::EXP_KBN_SYOHIN) . ".group_by");
        foreach ($orderBy as $by) {
            $qb->orderBy("t_zaiko_hokanryo.{$by}");
        }

        return $qb;
    }

    private function qbXTani($kbn)
    {
        $printOpt = data_get($this->getPrintOpts(), $kbn, self::EXP_KBN_SYOHIN);
        $qb = TZaikoHokanryo::query();
        $fields = [
            'ki1_kurikosi_su',
            'ki1_nyuko_su',
            'ki1_syuko_su',
            'ki2_kurikosi_su',
            'ki2_nyuko_su',
            'ki2_syuko_su',
            'ki3_kurikosi_su',
            'ki3_nyuko_su',
            'ki3_syuko_su',
            'touzan_su',
            'seki_su',
            'nyuko_su',
            'syuko_su',
            'hokan_kin',
            'nyuko_kin',
            'syuko_kin',
            'total_kin',
        ];
        foreach ($fields as $field) {
            $qb->selectRaw("SUM({$field}) AS $field");
        }
        $fields = [
            'tanka',
            'nyuko_tanka',
            'syuko_tanka',
        ];
        foreach ($fields as $field) {
            $qb->selectRaw("MAX({$field}) AS $field");
        }

        $qb->addSelect($printOpt['group_by']);
        if (!in_array('hinmei_cd', $printOpt['group_by'])) {
            $qb->selectRaw("NULL AS hinmei_cd");
        }

        $qb->groupBy($printOpt['group_by']);
        return $qb;
    }

    public function qbExport($request, $routeNm = null)
    {
        $model = new TZaikoHokanryo();

        $qbXTani = $this->qbXTani($request->get('export_kbn', self::EXP_KBN_SYOHIN));
        foreach ($request->all() as $key => $value) {
            $fromTo = ['bumon_cd'];
            foreach ($fromTo as $field) {
                if (is_null($value)) continue;
                if ($key == "{$field}_from") $qbXTani->where("t_zaiko_hokanryo.{$field}", '>=', $value);
                if ($key == "{$field}_to") $qbXTani->where("t_zaiko_hokanryo.{$field}", '<=', $value);
            }
        }

        $qb = TZaikoHokanryo::query()->fromSub($qbXTani, 't_zaiko_hokanryo');
        $qb->select('t_zaiko_hokanryo.*');
        $qb->addSelect('t_zaiko_hokanryo.ninusi_cd AS seikyu_cd');

        $qb->joinMNinusi()->addSelect([
            'm_ninusi.ninusi_ryaku_nm',
            'm_ninusi.ninusi1_nm',
            'm_ninusi.ninusi2_nm',
            'm_ninusi.kisei_kbn',

            // 集約請求先コード = 荷主コード
            'm_ninusi.ninusi_ryaku_nm AS seikyu_ninusi_ryaku_nm',
            'm_ninusi.ninusi1_nm AS seikyu_ninusi1_nm',
            'm_ninusi.ninusi2_nm AS seikyu_ninusi2_nm',
        ]);

        $qb->joinMSokoHinmei()->addSelect([
            'm_soko_hinmei.hinmei_nm',
            'm_soko_hinmei.kikaku',
            'm_soko_hinmei.irisu',
        ]);

        $qb->addSelectSuGroup('ki1_kurikosi');
        $qb->addSelectSuGroup('ki1_nyuko');
        $qb->addSelectSuGroup('ki1_syuko');
        $qb->addSelectSuGroup('ki2_kurikosi');
        $qb->addSelectSuGroup('ki2_nyuko');
        $qb->addSelectSuGroup('ki2_syuko');
        $qb->addSelectSuGroup('ki3_kurikosi');
        $qb->addSelectSuGroup('ki3_nyuko');
        $qb->addSelectSuGroup('ki3_syuko');
        $qb->addSelectSuGroup('touzan');

        foreach (['seki_su', 'nyuko_su', 'syuko_su'] as $su) {
            $qb->selectRaw($model::getSelectRawField('juryo', [
                'su' => $su,
                'bara_tani_juryo' => 'm_soko_hinmei.bara_tani_juryo'
            ]) . " AS " . preg_replace('/(\_su)$/', '_juryo', $su));
        }

        return $qb;
    }
}
