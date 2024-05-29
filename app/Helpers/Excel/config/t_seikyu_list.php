<?php
$exp = new \App\Helpers\Excel\XlsSeikyuList();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'BD', 'row' => 43],
                'size' => 33,
                'rowHeight' => 14
            ],
            'summary' => [
                'start' => ['col' => 'A', 'row' => 52],
                'end' => ['col' => 'BD', 'row' => 52],
                'mergeCells' => [
                    ['col' => 'D', 'row' => 1, 'w' => 6, 'h' => 1],
                ],
            ],
            'height' => 53,
        ],
        'summary' => [
            //前回請求額
            ['col' => 'J', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('zenkai_seikyu_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //入　金　額
            ['col' => 'N', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('nyukin_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //相殺・値引
            ['col' => 'R', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('sousai_nebiki');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //繰越額
            ['col' => 'V', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('kjrikosi_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //課税運賃
            ['col' => 'Z', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('kazei_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //消費税
            ['col' => 'AD', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('zei_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //非課税額
            ['col' => 'AH', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('hikazei_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //今回請求額
            ['col' => 'AL', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('konkai_torihiki_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //請　求　額
            ['col' => 'AP', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('seikyu_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
            //未確定件数
            ['col' => 'AT', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('mikakutei_su');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>3, 'h'=>1]],
            //未確定額
            ['col' => 'AW', 'row' => 1, 'value' => function ($groupCollection) {
                return $groupCollection->sum('mikakutei_kin');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w'=>4, 'h'=>1]],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'S', 'row' => 8],
            'mergeCells' => [
                ['col' => 'X', 'row' => 2, 'w' => 11, 'h' => 3],//請　求　一　覧　表
                ['col' => 'A', 'row' => 5, 'w' => 17, 'h' => 2],//日締
                ['col' => 'AT', 'row' => 3, 'w' => 7, 'h' => 2],//now
                ['col' => 'BA', 'row' => 3, 'w' => 4, 'h' => 2],//page-no

                ['col' => 'A', 'row' => 8, 'w' => 3, 'h' => 1],//コード
                ['col' => 'D', 'row' => 7, 'w' => 6, 'h' => 2],//請　求　先　名
                ['col' => 'J', 'row' => 7, 'w' => 4, 'h' => 2],//前回請求額
                ['col' => 'N', 'row' => 7, 'w' => 4, 'h' => 2],//入　金　額
                ['col' => 'R', 'row' => 7, 'w' => 4, 'h' => 2],//相殺・値引
                ['col' => 'V', 'row' => 7, 'w' => 4, 'h' => 2],//繰越額
                ['col' => 'Z', 'row' => 7, 'w' => 4, 'h' => 2],//課税運賃
                ['col' => 'AD', 'row' => 7, 'w' => 4, 'h' => 2],//消費税
                ['col' => 'AH', 'row' => 7, 'w' => 4, 'h' => 2],//非課税額
                ['col' => 'AL', 'row' => 7, 'w' => 4, 'h' => 2],//今回請求額
                ['col' => 'AP', 'row' => 7, 'w' => 4, 'h' => 2],//請　求　額

                ['col' => 'AT', 'row' => 7, 'w' => 3, 'h' => 1],//未確定件数
                ['col' => 'AT', 'row' => 8, 'w' => 3, 'h' => 1],//未確定件数

                ['col' => 'AW', 'row' => 7, 'w' => 4, 'h' => 2],//未確定額
                ['col' => 'BA', 'row' => 7, 'w' => 4, 'h' => 2],//回収予定

            ],
            'others' => [
                ['col' => 'A', 'row' => 5, 'type' => $exp::DATA_DATETIME, 'value' => function($page) {
                    return data_get($page->first(), 'seikyu_sime_dt');
                }],

                ['col' => 'AT', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'BA', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 9],
            'end' => ['col' => 'BD', 'row' => 9],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'ninusi_cd', 'mergeCells' => ['w'=>3, 'h'=>1]],//コード
            'D' => ['field' => 'ninusi1_nm', 'mergeCells' => ['w'=>6, 'h'=>1]],//請　求　先　名
            'J' => ['field' => 'zenkai_seikyu_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//前回請求額
            'N' => ['field' => 'nyukin_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//入　金　額
            'R' => ['field' => 'sousai_nebiki', 'mergeCells' => ['w'=>4, 'h'=>1]],//相殺・値引
            'V' => ['field' => 'kjrikosi_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//繰越額
            'Z' => ['field' => 'kazei_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//課税運賃
            'AD' => ['field' => 'zei_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//消費税
            'AH' => ['field' => 'hikazei_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//非課税額
            'AL' => ['field' => 'konkai_torihiki_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//今回請求額

            'AP' => ['field' => 'seikyu_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//請　求　額
            'AT' => ['field' => 'mikakutei_su', 'mergeCells' => ['w'=>3, 'h'=>1]],//未確定件数
            'AW' => ['field' => 'mikakutei_kin', 'mergeCells' => ['w'=>4, 'h'=>1]],//未確定額

            'BA' => ['value' => function($row) {
                return \App\Http\Repositories\Seikyu\SeikyuListRepository::getKaisyuYoteiDt(
                    data_get($row, 'seikyu_sime_dt'), data_get($row, 'kaisyu1_dd'), data_get($row, 'kaisyu2_dd')
                )->format('Y-m-d');
            }, 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w'=>4, 'h'=>1]],//回収予定
        ],
    ],
];

