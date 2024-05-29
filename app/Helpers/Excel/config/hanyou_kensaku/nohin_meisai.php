<?php
$exp = new \App\Helpers\Excel\XlsNohinMeisai();
$gokeiFunc = function($groupCollection) {
    return $groupCollection->sum(function ($item) {
        return data_get($item, 'kihon_unchin', 0) + data_get($item, 'tyukei_kin', 0);
    });
};
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'K', 'row' => 62],
                'size' => 47,
                //'rowHeight' => 14
            ],
            'summary' => [
                'start' => ['col' => 'A', 'row' => 64],
                'end' => ['col' => 'K', 'row' => 65],
                'mergeCells' => [
                    ['col' => 'F', 'row' => 1, 'w' => 2, 'h' => 1],
                    ['col' => 'F', 'row' => 2, 'w' => 2, 'h' => 1],
                ],
            ],
            'height' => 66,
        ],
        'summary' => [
            //合計
            ['col' => 'K', 'row' => 1, 'value' => function ($groupCollection) use ($gokeiFunc)  {
                $gokei = $gokeiFunc($groupCollection);
                switch (data_get($groupCollection->first(), 'zei_keisan_kbn', 0)) {
                    case '1': //消費税計算区分 = １：内税、
                        //「合計額」＝（SUM(明細.基本運賃) + SUM(明細.中継料)）/　1.1
                        //「合計（税込）額」＝（SUM(明細.基本運賃) + SUM(明細.中継料)）
                        return $gokei / (1 + configParam('TAX_RATE'));
                    default:
                        return $gokei;
                }
            }],
            //合計(税込)
            ['col' => 'K', 'row' => 2, 'value' => function ($groupCollection) use ($gokeiFunc) {
                $gokei = $gokeiFunc($groupCollection);
                switch (data_get($groupCollection->first(), 'zei_keisan_kbn', 0)) {
                    case '3'://消費税計算区分 =３：明細毎外税、「合計（税込）額」＝　SUM(税込金額)
                        return $groupCollection->sum('zeikomi_kingaku');
                    case '2': //消費税計算区分 = ２：請求一括外税、「合計（税込）額」＝　「合計額」ｘ　1.1
                        return $gokei * (1 + configParam('TAX_RATE'));
                    case '1': //消費税計算区分 = １：内税、
                        //「合計額」＝（SUM(明細.基本運賃) + SUM(明細.中継料)）/　1.1
                        //「合計（税込）額」＝（SUM(明細.基本運賃) + SUM(明細.中継料)）
                        return $gokei;
                    default: //消費税計算区分 = ０：計算無し、「合計（税込）額」＝　「合計額」
                        return $gokei;
                }
            }],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'K', 'row' => 13],
            'mergeCells' => [
                ['col' => 'A', 'row' => 2, 'w' => 11, 'h' => 1],//納　品　明　細
                ['col' => 'A', 'row' => 4, 'w' => 11, 'h' => 1],//配達日

                ['col' => 'A', 'row' => 13, 'w' => 5, 'h' => 1],//品名
                ['col' => 'F', 'row' => 13, 'w' => 2, 'h' => 1],//数量
            ],
            'others' => [
                ['col' => 'A', 'row' => 4, 'type' => $exp::DATA_DATETIME, 'value' => function($page) {
                    return data_get($page->first(), 'haitatu_dt');
                }],
                ['col' => 'A', 'row' => 9, 'value' => function($page) {
                    return data_get($page->first(), 'hachaku_nm');
                }],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 14],
            'end' => ['col' => 'K', 'row' => 14],
        ],
        'middle-block' => [
            'start' => ['col' => 'A', 'row' => 15],
            'end' => ['col' => 'K', 'row' => 15],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'hinmoku_nm'],//品目名
            'B' => ['field' => 'hinmei_nm'],//品名
            'E' => ['field' => 'syubetu_nm'],//種別
            'F' => ['field' => 'su', 'mergeCells' => ['w'=>2, 'h'=>1]],//数量
            'H' => ['field' => 'kihon_unchin'],//基本運賃
            'I' => ['field' => 'tyukei_kin'],//中継料
            'J' => ['field' => 'seikyu_kin_tax'],//消費税
            'K' => ['field' => 'zeikomi_kingaku'],//税込金額
        ],
    ],
];


