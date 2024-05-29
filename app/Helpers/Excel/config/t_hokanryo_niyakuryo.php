<?php
$exp = new \App\Helpers\Excel\XlsHokanryoNiyakuryo();
$suFunc = function ($row, $prefix) {
    return data_get($row, 'irisu', 1) > 1
        ? (
            \App\Helpers\Formatter::number(data_get($row, "{$prefix}_case_su"))
            . "(" . \App\Helpers\Formatter::number(data_get($row, "{$prefix}_hasu")) . ")"
        )
        : data_get($row, "{$prefix}_su");
};
$gokeiSuFunc = function ($groupCollection, $prefix) {
    return $groupCollection->sum("{$prefix}_case_su");
};
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'R', 'row' => 40],
                'size' => 9,
            ],
            'summary' => [
                'hinmokukei' => [
                    'start' => ['col' => 'A', 'row' => 45],
                    'end' => ['col' => 'R', 'row' => 47],
                ],
                'gokei' => [
                    'start' => ['col' => 'A', 'row' => 49],
                    'end' => ['col' => 'R', 'row' => 51],
                ]
            ],
            'height' => 52,
        ],
        'summary' => [
            'gokei' => [
                //１期　繰越
                ['col' => 'B', 'row' => 1, 'value' => function ($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki1_kurikosi');
                }, 'type' => $exp::DATA_CLOSURE/*, 'mergeCells' => ['w' => 2, 'h' => 1]*/],
                //１期　入庫
                ['col' => 'C', 'row' => 1, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki1_nyuko');
                }, 'mergeCells' => ['w'=>3, 'h'=>1]],
                //２期　繰越
                ['col' => 'F', 'row' => 1, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki2_kurikosi');
                }],
                //２期　入庫
                ['col' => 'G', 'row' => 1, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki2_nyuko');
                }],
                //３期　繰越
                ['col' => 'H', 'row' => 1, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki3_kurikosi');
                }],
                //３期　入庫
                ['col' => 'I', 'row' => 1, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki3_nyuko');
                }],
                //保管料　単価
                //荷役料　入庫
                ['col' => 'O', 'row' => 1, 'value' => function($groupCollection) {
                    return $groupCollection->sum("nyuko_su");
                }],
                //荷役料　入庫単価
                //荷役料　入庫料
                ['col' => 'Q', 'row' => 1, 'value' => function($groupCollection) {
                    return $groupCollection->sum("nyuko_kin");
                }],

                //１期　出庫
                ['col' => 'C', 'row' => 3, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki1_syuko');
                }, 'mergeCells' => ['w'=>3, 'h'=>1]],
                //２期　出庫
                ['col' => 'G', 'row' => 3, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki2_syuko');
                }],
                //３期　出庫
                ['col' => 'I', 'row' => 3, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'ki3_syuko');
                }],
                //当月　残数
                ['col' => 'J', 'row' => 3, 'value' => function($groupCollection) use ($gokeiSuFunc) {
                    return $gokeiSuFunc($groupCollection, 'touzan');
                }],
                //保管料　積数
                ['col' => 'K', 'row' => 3, 'value' => function($groupCollection) {
                    return $groupCollection->sum("seki_su");
                }],
                //保管料　保管料
                ['col' => 'L', 'row' => 3, 'value' => function($groupCollection){
                    return $groupCollection->sum("hokan_kin");
                }, 'mergeCells' => ['w'=>3, 'h'=>1]],
                //荷役料　出庫
                ['col' => 'O', 'row' => 3, 'value' => function($groupCollection) {
                    return $groupCollection->sum("syuko_su");
                }],
                //荷役料　出庫単価
                //荷役料　出庫料
                ['col' => 'Q', 'row' => 3, 'value' => function($groupCollection) {
                    return $groupCollection->sum("syuko_kin");
                }],
                //合計
                ['col' => 'R', 'row' => 3, 'value' => function($groupCollection) {
                    return $groupCollection->sum("total_kin");
                }],
            ],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'R', 'row' => 13],
            'mergeCells' => [
                ['col' => 'G', 'row' => 1, 'w' => 4, 'h' => 2],//保管料・荷役料請求計算書
                ['col' => 'P', 'row' => 4, 'w' => 3, 'h' => 1],//発行日

                ['col' => 'A', 'row' => 10, 'w' => 1, 'h' => 2],//商　　品　　名
                ['col' => 'A', 'row' => 12, 'w' => 1, 'h' => 2],//規　格（商品コード）入数

                ['col' => 'B', 'row' => 9, 'w' => 4, 'h' => 2],//１期
                ['col' => 'C', 'row' => 11, 'w' => 3, 'h' => 1],//入庫
                ['col' => 'C', 'row' => 13, 'w' => 3, 'h' => 1],//出庫

                ['col' => 'F', 'row' => 9, 'w' => 2, 'h' => 2],//２期

                ['col' => 'H', 'row' => 9, 'w' => 2, 'h' => 2],//３期

                ['col' => 'J', 'row' => 9, 'w' => 1, 'h' => 2],//当 月

                ['col' => 'K', 'row' => 9, 'w' => 4, 'h' => 2],//保　 管 　料
                ['col' => 'L', 'row' => 11, 'w' => 3, 'h' => 1],//単価
                ['col' => 'L', 'row' => 13, 'w' => 3, 'h' => 1],//保管料

                ['col' => 'O', 'row' => 9, 'w' => 3, 'h' => 2],//荷　 役 　料

                ['col' => 'R', 'row' => 12, 'w' => 1, 'h' => 2],//合計
            ],
            'others' => [
                ['col' => 'A', 'row' => 7, 'type' => $exp::DATA_DATETIME, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), 'seikyu_sime_dt');
                }],

                ['col' => 'R', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO],
                ['col' => 'A', 'row' => 4, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), 'ninusi1_nm');
                }],
                'hakko_dt' => ['col' => 'P', 'row' => 4, 'type' => $exp::DATA_DATETIME, 'value' => ''],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 14],
            'end' => ['col' => 'R', 'row' => 16],
        ],
        'others' => [
        ]
    ],
    'block' => [
        0 => [   // row1
            'A' => ['field' => 'hinmei_nm'],//商品名
            //１期　繰越
            'B' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki1_kurikosi');
            }],
            //１期　入庫
            'C' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki1_nyuko');
            }, 'mergeCells' => ['w'=>3, 'h'=>1]],

            //２期　繰越
            'F' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki2_kurikosi');
            }],
            //２期　入庫
            'G' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki2_nyuko');
            }],

            //３期　繰越
            'H' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki3_kurikosi');
            }],
            //３期　入庫
            'I' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki3_nyuko');
            }],
            //保管料　単価
            'L' => ['field' => 'tanka', 'mergeCells' => ['w'=>3, 'h'=>1]],
            //荷役料　入庫
            'O' => ['field' => 'nyuko_su'],
            //荷役料　入庫単価
            'P' => ['field' => 'nyuko_tanka'],
            //荷役料　入庫料
            'Q' => ['field' => 'nyuko_kin'],
        ],
        1 => [],
        2 => [
            'A' => ['field' => 'kikaku'],//規格
            //１期　出庫
            'C' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki1_syuko');
            }, 'mergeCells' => ['w'=>3, 'h'=>1]],
            //２期　出庫
            'G' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki2_syuko');
            }],
            //３期　出庫
            'I' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'ki3_syuko');
            }],
            //当月　残数
            'J' => ['value' => function($row) use ($suFunc) {
                return $suFunc($row, 'touzan');
            }],
            //保管料　積数
            'K' => ['field' => 'seki_su'],
            //保管料　保管料
            'L' => ['field' => 'hokan_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],
            //荷役料　出庫
            'O' => ['field' => 'syuko_su'],
            //荷役料　出庫単価
            'P' => ['field' => 'syuko_tanka'],
            //荷役料　出庫料
            'Q' => ['field' => 'syuko_kin'],
            //合計
            'R' => ['field' => 'total_kin'],
        ],
    ],

    'EXP_KBN_SEIKYU' => function(&$config) {
        unset($config['block'][0]['A']);
        unset($config['base']['summary']['hinmokukei']);
    },
    'EXP_HIDE_EXP_DT' => function(&$config) {
        unset($config['base']['header']['others']['hakko_dt']);
    },
];

