<?php


namespace App\Http\Repositories;


use App\Models\TUriage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JyutyuRespository
{
    const EXP_PRINT_OTHER_CSV_HEADER = '1';
    public function getExportInjiGroupOpts()
    {
        return [
            'syuka_dt' => [
                'text' => '集荷日',
                'orderBy' => ['syuka_dt']
            ],
            'haitatu_dt' => [
                'text' => '配達日',
                'orderBy' => ['haitatu_dt']
            ],
        ];
    }

    public function getExportSyuturyokuKbnOpts()
    {
        return [
            '1' => [
                'text' => '指定無',
                'where' => function (&$qb, $table = 't_uriage') {
                    // TODO: nothing
                }
            ],
            '2' => [
                'text' => '未配車',
                'where' => function (&$qb, $table = 't_uriage') {
                    $qb->where(function ($q) use ($table) {
                        $q->whereNull("{$table}.syaban")
                            ->orWhere("{$table}.syaban", '=', '');
                    });
                }
            ],
            '3' => [
                'text' => '配車済',
                'where' => function (&$qb, $table = 't_uriage') {
                    $qb->whereNotNull("{$table}.syaban")
                        ->where("{$table}.syaban", '!=', '');

                }
            ],
        ];
    }

    public function getExportPrintOtherOpts() {
        return [
            $this::EXP_PRINT_OTHER_CSV_HEADER => ['text' => 'CSV見出し出力'],
            '2' => [
                'text' => '売上計上済を出力する',
                'where' => function (&$qb, $table = "t_uriage") {
                    $qb->whereNotNull("{$table}.seikyu_keijyo_dt");
                }
            ],
            '3' => [
                'text' => '売上未計上を出力する',
                'where' => function (&$qb, $table = "t_uriage") {
                    $qb->whereNull("{$table}.seikyu_keijyo_dt");
                }
            ],
            '4' => [
                'text' => '売上計上無しを出力する',
                'where' => function (&$qb, $table = "t_uriage") {
                    $qb->where(function ($q) use ($table) {
                        $q->whereNull("{$table}.su")->orWhere("{$table}.su", '=', 0);
                    });
                }
            ],
        ];
    }

    public function qbExport($request, $route = null, &$tUriage = null)
    {
        $tUriage = new TUriage();
        $table = $tUriage->getTable();
        $qb = $tUriage->filter($request);// $qb = new Builder();
        $qb->select("{$table}.*");
        // 部門マスタ
        $qb->joinMBumon()->addSelect("bumon_nm");
        // 荷主マスタ
        $qb->joinMNinusi()->addSelect("m_ninusi.ninusi_ryaku_nm");
        // 発地着地マスタ
        $qb->joinMHachaku()->addSelect("m_hachaku.hachaku_nm");
        $qb->JoinHatuti()->addSelect("m_hatuti.hachaku_nm AS hatuti_nm");
        // 庸車先マスタ
        //$qb->joinMYousya()->addSelect("yousya1_nm");
        // 品名マスタ, 品目マスタ
        $qb->joinMHinmei('left', 'm_hinmei', true)->addSelect(["hinmei_nm", "m_hinmei.hinmoku_cd", "hinmoku_nm"]);
        // 名称マスタ
        $qb->joinMMeisyoJyutyu()->addSelect("m_meisyo_jyutyu.meisyo_nm AS jyutyu_kbn_nm");
        $qb->joinMMeisyoTani()->addSelect("m_meisyo_tani.meisyo_nm AS tani_nm");

        switch ($route) {
            case 'jyutyu.exp.pdf':
            case 'jyutyu.exp.xls':
                // 基本運賃+中継料+通行料等+集荷料+手数料
                // unchin_kin+tyukei_kin+tukoryo_kin+syuka_kin+tesuryo_kin
                $qb->addSelect(DB::raw("(
                    COALESCE(unchin_kin, 0) 
                    + COALESCE(tyukei_kin, 0) 
                    + COALESCE(tukoryo_kin, 0) 
                    + COALESCE(syuka_kin, 0)
                    + COALESCE(tesuryo_kin, 0)
                ) AS unchin"));
                break;
            case 'jyutyu.exp.csv':
                // 庸車先マスタ
                $qb->joinMYousya()->addSelect("yousya1_nm");
                // 乗務員マスタ
                $qb->joinMJyomuin()->addSelect("jyomuin_nm");
                // 名称マスタ
                $qb->joinMMeisyoGyosya()->addSelect("m_meisyo_gyosya.meisyo_nm AS gyosya_nm");
                $qb->joinMMeisyoGenkin()->addSelect("m_meisyo_genkin.meisyo_nm AS genkin_nm");
                $qb->joinMMeisyoTanka()->addSelect("m_meisyo_tanka.meisyo_nm AS tanka_nm");
                break;
        }

        return $qb;
    }

    public function applyRequestToBuilder(Request $request) {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $tUriage = null;
        $qb = $this->qbExport($cloneReq, $routeNm, $tUriage);

        // 出力方法
        $orderBy = data_get($this->getExportInjiGroupOpts(), @$cloneReq->inji_group . ".orderBy", ['syuka_dt']);
        //$qb->whereNotNull($orderBy[0]);
        $qb->orderBy(...$orderBy);
        //$qb->whereNotNull($tUriage->getTable() . '.bumon_cd');
        $qb->orderBy('bumon_cd');

        // 出力区分
        $opts = $this->getExportSyuturyokuKbnOpts();
        $func = $opts[$cloneReq->syuturyoku_kbn]['where'];
        $qb->where($func($qb));

        // 印刷順
        $orderBy = collect([]);
        foreach (data_get($cloneReq, 'print_order', []) as $field => $index) {
            $orderBy->push(['field' => $field, 'index' => $index]);
        }
        $orderBy = $orderBy->sortBy('index');
        foreach ($orderBy as $order) {
            $qb->orderBy($order['field']);
        }
        $qb->orderBy('uriage_den_no');

        // その他
        $opts = $this->getExportPrintOtherOpts();
        unset($opts[$this::EXP_PRINT_OTHER_CSV_HEADER]);
        foreach(array_intersect(array_keys($opts), data_get($cloneReq, 'print_other', [])) as $key) {
            $func = $opts[$key]['where'] ?? null;
            if ($func) $qb->where($func($qb));
        }

        return $qb;
    }
}
