<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'I', 'row' => 34],
                'size' => 13,
            ],
            'summary' => [
                'bumon_cd' => [
                    'start' => ['col' => 'A', 'row' => 40],
                    'end' => ['col' => 'I', 'row' => 41],
                ],
                'ooo_dt' => [
                    'start' => ['col' => 'A', 'row' => 43],
                    'end' => ['col' => 'I', 'row' => 44],
                ]
            ],
            'height' => 45,
        ],
        'groupBy' => ['syuka_dt', 'bumon_cd'],
        'summary' => [
            'bumon_cd' => [
                ['col' => 'E', 'row' => 1, 'value' => function ($bumonCollection) {
                    return $bumonCollection->sum('su');
                }, 'type' => $exp::DATA_CLOSURE],//数量
                ['col' => 'G', 'row' => 1, 'value' => function ($bumonCollection) {
                    return $bumonCollection->sum('unchin');
                }, 'type' => $exp::DATA_CLOSURE],//運賃
                ['col' => 'H', 'row' => 1, 'value' => function ($bumonCollection) {
                    return $bumonCollection->sum('yosya_tyukei_kin');
                }, 'type' => $exp::DATA_CLOSURE],//傭車料

                ['col' => 'G', 'row' => 2, 'value' => function ($bumonCollection) {
                    return $bumonCollection->sum('tukoryo_kin');
                }, 'type' => $exp::DATA_CLOSURE],//付帯非課税
                ['col' => 'H', 'row' => 2, 'value' => function ($bumonCollection) {
                    return $bumonCollection->sum('yosya_tukoryo_kin');
                }, 'type' => $exp::DATA_CLOSURE],//付帯非課税
            ],
            'ooo_dt' => [
                ['col'=> 'E', 'row' => 2,  'value' => function($dateCollection) {
                    return $dateCollection->sum(function ($bumonCollection) {
                        return $bumonCollection->sum('su');
                    });
                }, 'type' => $exp::DATA_CLOSURE],
                ['col'=> 'G', 'row' => 1,  'value' => function($dateCollection) {
                    return $dateCollection->sum(function ($bumonCollection) {
                        return $bumonCollection->sum('unchin');
                    });
                }, 'type' => $exp::DATA_CLOSURE],
                ['col'=> 'H', 'row' => 1,  'value' => function($dateCollection) {
                    return $dateCollection->sum(function ($bumonCollection) {
                        return $bumonCollection->sum('yosya_tyukei_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE],
                ['col'=> 'G', 'row' => 2,  'value' => function($dateCollection) {
                    return $dateCollection->sum(function ($bumonCollection) {
                        return $bumonCollection->sum('tukoryo_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE],
                ['col'=> 'H', 'row' => 2,  'value' => function($dateCollection) {
                    return $dateCollection->sum(function ($bumonCollection) {
                        return $bumonCollection->sum('yosya_tukoryo_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE],
            ],

        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'I', 'row' => 6],
            'mergeCells' => [
                ['col' => 'D', 'row' => 2, 'w' => 2, 'h' => 1],
                ['col' => 'B', 'row' => 5, 'w' => 2, 'h' => 1],
                ['col' => 'B', 'row' => 6, 'w' => 2, 'h' => 1],
                ['col' => 'I', 'row' => 5, 'w' => 1, 'h' => 2],
            ],
            'others' => [
                ['col' => 'A', 'row' => 3, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return data_get($page, "0.bumon_cd", '') .':' . data_get($page, "0.bumon_nm", '');
                }],
                ['col' => 'I', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 7],
            'end' => ['col' => 'I', 'row' => 8],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'ninusi_ryaku_nm'],//荷主名	m_ninusi.ninusi_ryaku_nm
            'B' => ['field' => 'hachaku_nm'],//発地名  hachaku_nm
            'C' => ['value' => function($row) {
                return \App\Helpers\Formatter::datetime(data_get($row, 'syuka_dt'), 'm/d');
            }, 'type' => $exp::DATA_CLOSURE],//発地名  hachaku_nm
            'D' => ['value' => function($row) {
                return data_get($row, 'hinmoku_nm') . ' ' . data_get($row, 'hinmei_nm');
            }],//品名  m_hinmei.hinmei_nm
            'E' => ['value' => function($row) {
                return \App\Helpers\Formatter::number(data_get($row, 'su')) . data_get($row, 'tani_nm');
            }, 'type' => $exp::DATA_CLOSURE],//数量
            'F' => ['field' => 'seikyu_tanka'],//請求単価
            'G' => ['field' => 'unchin'],//運賃
            'H' => ['field' => 'yosya_tyukei_kin'],//傭車料
            'I' => ['value' => function($row) {
                return data_get($row, 'syaban') ? '*' : '';
            }],//傭車料
        ],
        [   // row2
            'A' => ['field' => 'jyutyu_kbn_nm'],//受注区分
            'B' => ['field' => 'hatuti_nm'],//着地
            'C' => ['value' => function($row) {
                $jikoku = data_get($row, 'jikoku', '');
                return \App\Helpers\Formatter::datetime(data_get($row, 'haitatu_dt'), 'm/d')
                        . ($jikoku ? ' ' . \App\Helpers\Formatter::datetime($jikoku, 'H:i') . '指' : '');
            }, 'type' => $exp::DATA_CLOSURE],//着地
            'D' => ['field' => 'biko'],//備考
            //'D' => ['field' => ''],
            //'E' => ['field' => ''],//傭車単価
            'G' => ['field' => 'tukoryo_kin'],//付帯非課税
            'H' => ['field' => 'yosya_tukoryo_kin'],//付帯非課税
            'I' => ['field' => 'uriage_den_no'],//売上番号
        ],
    ],
];
