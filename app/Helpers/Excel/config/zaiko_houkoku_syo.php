<?php
$exp = new \App\Helpers\Excel\XlsZaikoHoukokuSyo();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'I', 'row' => 53],
                'size' => 22
            ],
            'height' => 57,
            'summary' => [
                'total' => [
                    'start' => ['col' => 'A', 'row' => 55],
                    'end' => ['col' => 'I', 'row' => 56]
                ],
            ]
        ],
        'summary' => [
            'total' => [
                ['col' => 'G', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2]],
                ['col' => 'H', 'row' => 1, 'value' => function($list) {
                    return $list->sum('case_su');
                }],
                ['col' => 'H', 'row' => 2, 'value' => function($list) {
                    return $list->sum('hasu');
                }],
                ['col' => 'I', 'row' => 2, 'value' => function($list) {
                    return $list->sum('su');
                }],
            ],
        ],
        'groupBy' => [],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'I', 'row' => 9],
            'mergeCells' => [
                ['col' => 'C', 'row' => 9, 'w' => 2, 'h' => 1],//規格/入数
            ],
            'others' => [
                ['col' => 'I', 'row' => 2, 'constVal' => $exp::VAL_PAGE_NO],
                ['col' => 'A', 'row' => 4, 'value' => function($list) {
                    return data_get($list->first(), 'ninusi1_nm', '');
                }],
                ['col' => 'A', 'row' => 5, 'value' => function($list) {
                    return data_get($list->first(), 'ninusi2_nm', '');
                }],

                ['col' => 'A', 'row' => 8, 'type' => $exp::DATA_DATETIME, 'value' => function() {
                    if (!empty(request()->get('exp')['kijyun_dt'])) {
                        $kijyun_dt = request()->get('exp')['kijyun_dt'];
                        $carbon = \Carbon\Carbon::createFromFormat('Y/m/d', $kijyun_dt);
                        if ($carbon instanceof \Carbon\Carbon) {
                            return $kijyun_dt;
                        }
                    }
                }],

            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 10],
            'end' => ['col' => 'I', 'row' => 11],
        ],
    ],
    'block' => [
        [
            'A' => ['field' => 'hinmei_nm'],//商品名
            'B' => ['field' => 'case_nm'],//単位
            'C' => ['field' => 'kikaku'],//規格
            'E' => ['field' => 'lot1', 'mergeCells' => ['w' => 1, 'h' => 2]],
            'F' => ['field' => 'lot2', 'mergeCells' => ['w' => 1, 'h' => 2]],
            'G' => ['field' => 'lot3', 'mergeCells' => ['w' => 1, 'h' => 2]],
            'H' => ['field' => 'case_su'],
        ],
        [
            'A' => ['field' => 'hinmei_cd'],//商品コード
            'B' => ['field' => 'bara_tani_nm'],//単位
            'C' => ['field' => 'irisu'],//入数
            'H' => ['field' => 'hasu'],//端数
            'I' => ['field' => 'sousu'],//総数
        ]
    ],

    'hide_lot' => function (&$config) {
        $config['base']['header']['others'][] = ['col' => 'E', 'row' => 9, 'value' => ' '];
        $config['base']['header']['others'][] = ['col' => 'F', 'row' => 9, 'value' => ' '];
        $config['base']['header']['others'][] = ['col' => 'G', 'row' => 9, 'value' => ' '];
        unset($config['block'][0]['E']);
        unset($config['block'][0]['F']);
        unset($config['block'][0]['G']);
    },
];
