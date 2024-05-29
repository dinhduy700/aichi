<?php
$exp = new \App\Helpers\Excel\XlsZaikoList();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'N', 'row' => 48],
                'size' => 20,
            ],
            'summary' => [
                'by_ninusi' => [
                    'start' => ['col' => 'A', 'row' => 55],
                    'end' => ['col' => 'N', 'row' => 56],
                ],
            ],
            'height' => 57,
        ],
        'summary' => [
            'by_ninusi' => [
                ['col' => 'E', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2]],
                ['col' => 'K', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function ($groupCollection) {
                    return $groupCollection->sum('sousu');
                }],
                ['col' => 'L', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function ($groupCollection) {
                    return $groupCollection->sum('jyuryo');
                }],
                'kingaku' => ['col' => 'M', 'row' => 1, 'mergeCells' => ['w' => 2, 'h' => 2], 'value' => function ($groupCollection) {
                    return $groupCollection->sum('kingaku');
                }],
            ],
        ],
        //'groupBy' => [],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'N', 'row' => 8],
            'mergeCells' => [
                ['col' => 'C', 'row' => 2, 'w' => 9, 'h' => 2],//在庫一覧表
                ['col' => 'L', 'row' => 2, 'w' => 2, 'h' => 1],//now
                ['col' => 'A', 'row' => 6, 'w' => 2, 'h' => 1],//kijun

                ['col' => 'A', 'row' => 7, 'w' => 2, 'h' => 2],//荷主名
                ['col' => 'C', 'row' => 7, 'w' => 1, 'h' => 2],//商品コード
                ['col' => 'D', 'row' => 7, 'w' => 1, 'h' => 2],//商品名
                ['col' => 'E', 'row' => 7, 'w' => 1, 'h' => 2],//規格
                ['col' => 'F', 'row' => 7, 'w' => 1, 'h' => 2],//ロット１
                ['col' => 'G', 'row' => 7, 'w' => 1, 'h' => 2],//ロット2
                ['col' => 'H', 'row' => 7, 'w' => 1, 'h' => 2],//ロット3
                //ケース/端数
                ['col' => 'J', 'row' => 7, 'w' => 1, 'h' => 2],//単位
                ['col' => 'K', 'row' => 7, 'w' => 1, 'h' => 2],//総数
                ['col' => 'L', 'row' => 7, 'w' => 1, 'h' => 2],//重量
                ['col' => 'M', 'row' => 7, 'w' => 2, 'h' => 2],//金額
            ],
            'others' => [
                ['col' => 'L', 'row' => 2, 'constVal' => $exp::VAL_CURRENT_TIME],//now
                ['col' => 'N', 'row' => 2, 'constVal' => $exp::VAL_PAGE_NO],// page - 貢
                ['col' => 'A', 'row' => 5, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), "soko_cd", '');
                }],
                ['col' => 'B', 'row' => 5, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), "soko_nm", '');
                }],
                'kijyun_dt' => ['col' => 'A', 'row' => 6, 'type' => $exp::DATA_DATETIME],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 9],
            'end' => ['col' => 'N', 'row' => 10],
        ],
    ],
    'block' => [
        0 => [
            'A' => ['field' => 'ninusi_ryaku_nm', 'mergeCells' => ['w' => 2, 'h' => 2]],//荷主名
            'C' => ['field' => 'hinmei_cd', 'mergeCells' => ['w' => 1, 'h' => 2]],//商品コード
            'D' => ['field' => 'hinmei_nm', 'mergeCells' => ['w' => 1, 'h' => 2]],//商品名
            'E' => ['field' => 'kikaku', 'mergeCells' => ['w' => 1, 'h' => 2]],//規格
            'F' => ['field' => 'lot1', 'mergeCells' => ['w' => 1, 'h' => 2]],//ロット１
            'G' => ['field' => 'lot2', 'mergeCells' => ['w' => 1, 'h' => 2]],//ロット2
            'H' => ['field' => 'lot3', 'mergeCells' => ['w' => 1, 'h' => 2]],//ロット3
            'I' => ['field' => 'case_su'],//ケース
            'J' => ['field' => 'case_nm'],//単位

            'K' => ['field' => 'sousu', 'mergeCells' => ['w' => 1, 'h' => 2]],
            'L' => ['field' => 'jyuryo', 'mergeCells' => ['w' => 1, 'h' => 2]],
            'M' => ['field' => 'kingaku', 'mergeCells' => ['w' => 2, 'h' => 2]],
        ],
        1 => [
            'I' => ['field' => 'hasu'],//端数
            'J' => ['field' => 'bara_tani_nm'],//単位
        ]
    ],
    'hide_lot' => function (&$config) {
        $config['base']['header']['others'][] = ['col' => 'F', 'row' => 7, 'value' => ' '];
        $config['base']['header']['others'][] = ['col' => 'G', 'row' => 7, 'value' => ' '];
        $config['base']['header']['others'][] = ['col' => 'H', 'row' => 7, 'value' => ' '];
        unset($config['block'][0]['F']);
        unset($config['block'][0]['G']);
        unset($config['block'][0]['H']);
    },
    'hide_kingaku' => function(&$config) {
        $config['base']['header']['others'][] = ['col' => 'M', 'row' => 7, 'value' => ' '];
        $config['block'][0]['M'] = ['value' => '', 'mergeCells' => ['w' => 2, 'h' => 2]];
        $config['base']['summary']['by_ninusi']['kingaku']['value'] = '';
    },
];

