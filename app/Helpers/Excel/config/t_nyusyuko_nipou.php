<?php
$exp = new \App\Helpers\Excel\XlsNyusyukoNipou();
$valCaseHasu = function ($row, $caseField, $hasuField) {
    if ($row->$caseField===null && $row->$hasuField===null) return '';
    return \App\Helpers\Formatter::number($row->$caseField) . "/"
        . \App\Helpers\Formatter::number($row->$hasuField);
};
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'P', 'row' => 45],
                'size' => 17,
            ],
            'summary' => [
                'start' => ['col' => 'A', 'row' => 47],
                'end' => ['col' => 'P', 'row' => 48],
                'mergeCells' => [
                ],
            ],
            'height' => 50,
        ],
        'summary' => [
            ['col' => 'F', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('last_su');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],
            ['col' => 'G', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('last_juryo');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],

            ['col' => 'I', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('in_su');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],
            ['col' => 'J', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('in_juryo');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],

            ['col' => 'L', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('out_su');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],
            ['col' => 'M', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('out_juryo');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],

            ['col' => 'O', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('curt_su');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],
            ['col' => 'P', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('curt_jyuryo');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>1, 'h'=>2]],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'P', 'row' => 9],
            'mergeCells' => [
                ['col' => 'C', 'row' => 2, 'w' => 9, 'h' => 2],//□□入出庫日報□□
                ['col' => 'M', 'row' => 3, 'w' => 3, 'h' => 1],//current-time

                ['col' => 'A', 'row' => 8, 'w' => 1, 'h' => 2],//商品コード
                ['col' => 'D', 'row' => 8, 'w' => 1, 'h' => 2],//単位
                ['col' => 'E', 'row' => 8, 'w' => 3, 'h' => 1],//前日繰越
                ['col' => 'H', 'row' => 8, 'w' => 3, 'h' => 1],//当日入庫
                ['col' => 'K', 'row' => 8, 'w' => 3, 'h' => 1],//当日出庫
                ['col' => 'N', 'row' => 8, 'w' => 3, 'h' => 1],//当日残高
            ],
            'others' => [
                ['col' => 'M', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'P', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO],

                ['col' => 'A', 'row' => 4, 'value' => function($page) {
                    return data_get($page->first(), "bumon_cd", "");
                }],
                ['col' => 'B', 'row' => 4, 'value' => function($page) {
                    return data_get($page->first(), "bumon_nm", "");
                }],

                ['col' => 'A', 'row' => 6, 'value' => function($page) {
                    return data_get($page->first(), "ninusi_cd", '');
                }, 'type' => $exp::DATA_STRING],
                ['col' => 'B', 'row' => 6, 'value' => function($page) {
                    return data_get($page->first(), "ninusi_ryaku_nm", '');
                }],
                'kijyun_dt' => ['col' => 'C', 'row' => 6, 'type' => $exp::DATA_DATETIME, 'value' => '基準日'],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 10],
            'end' => ['col' => 'P', 'row' => 11],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'hinmei_cd'],//商品コード
            'B' => ['field' => 'hinmei_nm'],//商品名
            'C' => ['value' => function($row) {
                return data_get($row, 'soko_cd') . '：' . data_get($row, 'location');
            }],//倉庫/ロケーション
            'D' => ['field' => 'case_nm', 'mergeCells' => ['w'=>1, 'h'=>2]],//単位

            'E' => ['field' => 'last_case_su'],//前日残高 > ケース/端数
            'F' => ['field' => 'last_su', 'mergeCells' => ['w'=>1, 'h'=>2]],//前日残高 > 総数
            'G' => ['field' => 'last_juryo', 'mergeCells' => ['w'=>1, 'h'=>2]],//前日残高 > 重量

            'H' => ['field' => 'in_case_su'],//当日入庫 > ケース/端数
            'I' => ['field' => 'in_su', 'mergeCells' => ['w'=>1, 'h'=>2]],//当日入庫 > 総数
            'J' => ['field' => 'in_juryo', 'mergeCells' => ['w'=>1, 'h'=>2]],//当日入庫 > 重量

            'K' => ['field' => 'out_case_su'],//当日出庫 > ケース/端数
            'L' => ['field' => 'out_su', 'mergeCells' => ['w'=>1, 'h'=>2]],//当日出庫 > 総数
            'M' => ['field' => 'out_juryo', 'mergeCells' => ['w'=>1, 'h'=>2]],//当日出庫 > 重量

            'N' => ['field' => 'curt_case_su'],//当日残高 > ケース/端数
            'O' => ['field' => 'curt_su', 'mergeCells' => ['w'=>1, 'h'=>2]],//当日残高 > 総数
            'P' => ['field' => 'curt_jyuryo', 'mergeCells' => ['w'=>1, 'h'=>2]],//当日残高 > 重量
        ],
        [   // row2
            'B' => ['field' => 'kikaku'],//規格・ロット
            'E' => ['field' => 'last_hasu_su'],//前日残高 > ケース/端数
            'H' => ['field' => 'in_hasu_su'],//当日入庫 > ケース/端数
            'K' => ['field' => 'out_hasu_su'],//当日出庫 > ケース/端数
            'N' => ['field' => 'curt_hasu_su'],//当日残高 > ケース/端数
        ],
    ],
];
