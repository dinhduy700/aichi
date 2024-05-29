<?php
$exp = new \App\Helpers\Excel\XlsRyoshusho();
$sumMeisaiKingaku = function ($groupCollection) {
    return $groupCollection->sum('kingaku');
};
$summaryTax = function ($groupCollection) use ($sumMeisaiKingaku) {
    switch (data_get($groupCollection->first(), 'zei_keisan_kbn', 0)) {
        case '3'://荷主マスタ.消費税計算区分 = ３：明細毎外税, 消費税 = SUM(売上データの消費税(seikyu_kin_tax))
            return $groupCollection->sum('seikyu_kin_tax');
        case '2': //荷主マスタ.消費税計算区分 = ２：請求一括外税, 消費税 = SUM(明細.金額) * 0.1
            return $sumMeisaiKingaku($groupCollection) * configParam('TAX_RATE');
        case '1': //荷主マスタ.消費税計算区分 = １：内税, 消費税 = SUM(明細.金額) - SUM(明細.金額)/1.1
            return $sumMeisaiKingaku($groupCollection) * (1 - 1/(1 + configParam('TAX_RATE')));
        default: //消費税計算区分 = ０：計算無し
            return 0;
    }
};
$summaryGokei = function ($groupCollection) use ($sumMeisaiKingaku, $summaryTax) {
    return $sumMeisaiKingaku($groupCollection) + $summaryTax($groupCollection);
};
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'R', 'row' => 45],
                'size' => 32,
            ],
            'summary' => [
                'start' => ['col' => 'A', 'row' => 47],
                'end' => ['col' => 'R', 'row' => 62],
                'mergeCells' => [
                    //消費税
                    ['col' => 'H', 'row' => 1, 'w' => 3, 'h' => 1],
                    ['col' => 'K', 'row' => 1, 'w' => 4, 'h' => 1],
                    //合　計
                    ['col' => 'H', 'row' => 2, 'w' => 3, 'h' => 1],
                    ['col' => 'K', 'row' => 2, 'w' => 4, 'h' => 1],

                    //領　収　書 ========================
                ],
            ],
            'height' => 65,
        ],
        'summary' => [
            //消費税
            'tax' => ['col' => 'K', 'row' => 1, 'value' => function ($groupCollection) use ($summaryTax) {
                return $summaryTax($groupCollection);
            }],
            //合　計
            'sum' => ['col' => 'K', 'row' => 2, 'value' => function ($groupCollection) use ($summaryGokei) {
                return $summaryGokei($groupCollection);
            }],

            //領　収　書 ========================
            //配達日
            ['col' => 'Q', 'row' => 7, 'type' => $exp::DATA_DATETIME, 'value' => function ($groupCollection) {
                return data_get($groupCollection->first(), 'haitatu_dt');
            }],
            //着地
            ['col' => 'C', 'row' => 8, 'value' => function ($groupCollection) {
                return data_get($groupCollection->first(), 'hachaku_nm');
            }],
            //合計
            'ryoshusho_sum' => ['col' => 'A', 'row' => 10, 'value' => function ($groupCollection) use ($summaryGokei) {
                return $summaryGokei($groupCollection);
            }],
            //消費税
            'ryoshusho_tax' => ['col' => 'P', 'row' => 11, 'value' => function ($groupCollection) use ($summaryTax) {
                return $summaryTax($groupCollection);
            }],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'K', 'row' => 13],
            'mergeCells' => [
                ['col' => 'A', 'row' => 13, 'w' => 7, 'h' => 1],//品名
                ['col' => 'H', 'row' => 13, 'w' => 3, 'h' => 1],//数量
                ['col' => 'K', 'row' => 13, 'w' => 4, 'h' => 1],//金額
                ['col' => 'O', 'row' => 13, 'w' => 4, 'h' => 1],//備考
            ],
            'others' => [
                ['col' => 'A', 'row' => 4, 'type' => $exp::DATA_DATETIME, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), 'haitatu_dt');
                }],
                ['col' => 'B', 'row' => 9, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), 'hachaku_nm');
                }],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 14],
            'end' => ['col' => 'R', 'row' => 14],
        ],
        'middle-block' => [
            'start' => ['col' => 'A', 'row' => 15],
            'end' => ['col' => 'R', 'row' => 15],
        ],
        'last-block' => [
            'start' => ['col' => 'A', 'row' => 45],
            'end' => ['col' => 'R', 'row' => 45],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'hinmei_nm'],//品名
            'H' => ['field' => 'su', 'mergeCells' => ['w'=>3, 'h'=>1]],//数量
            'K' => ['field' => 'kingaku', 'mergeCells' => ['w'=>4, 'h'=>1]],//金額
            'O' => ['field' => 'biko'],//備考
        ],
    ],
];
