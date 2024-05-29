<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();

return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'BL', 'row' => 91],
                'size' => 27
            ],
            'height' => 91,
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'BL', 'row' => 10],
            'mergeCells' => [
                ['col' => 'X', 'row' => 1, 'w' => 17, 'h' => 3],
                ['col' => 'BC', 'row' => 3, 'w' => 7, 'h' => 1],
                ['col' => 'D', 'row' => 4, 'w' => 6, 'h' => 2],
                ['col' => 'J', 'row' => 4, 'w' => 11, 'h' => 2],
                ['col' => 'N', 'row' => 6, 'w' => 7, 'h' => 2],
                ['col' => 'V', 'row' => 6, 'w' => 7, 'h' => 2],
                ['col' => 'B', 'row' => 8, 'w' => 4, 'h' => 2],
                ['col' => 'F', 'row' => 8, 'w' => 5, 'h' => 2],
                ['col' => 'W', 'row' => 8, 'w' => 6, 'h' => 2],
                ['col' => 'AC', 'row' => 8, 'w' => 4, 'h' => 2],
                ['col' => 'AG', 'row' => 8, 'w' => 4, 'h' => 2],
                ['col' => 'AK', 'row' => 8, 'w' => 4, 'h' => 2],

                ['col' => 'AO', 'row' => 8, 'w' => 8, 'h' => 2],
                ['col' => 'AO', 'row' => 10, 'w' => 4, 'h' => 1],
                ['col' => 'AS', 'row' => 10, 'w' => 4, 'h' => 1],

                ['col' => 'AW', 'row' => 8, 'w' => 8, 'h' => 2],
                ['col' => 'AW', 'row' => 10, 'w' => 4, 'h' => 1],
                ['col' => 'BA', 'row' => 10, 'w' => 4, 'h' => 1],

                ['col' => 'BE', 'row' => 8, 'w' => 8, 'h' => 2],
                ['col' => 'BE', 'row' => 10, 'w' => 4, 'h' => 1],
                ['col' => 'BI', 'row' => 10, 'w' => 4, 'h' => 1],
            ],
            'others' => [
               

                ['col' => 'J', 'row' => 4, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return App\Helpers\Formatter::dateJP(now(), 'y年m月d日') . '時点';
                }],

                ['col' => 'E', 'row' => 6, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first      = $page->first();
                    $bumonCd    = !empty($first) ? $first->bumon_cd : '';
                    $bumonNm    = !empty($first) ? $first->bumon_nm : '';

                    $res = $bumonCd . ': '. $bumonNm;

                    return $res;
                }],

                ['col' => 'V', 'row' => 6, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first      = $page->first();
                    $sokoCd    = !empty($first) ? $first->soko_cd : '';
                    $sokoNm    = !empty($first) ? $first->soko_nm : '';

                    $res = $sokoCd . ': '. $sokoNm;

                    return $res;
                }],

               
                ['col' => 'BC', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'BJ', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 11],
            'end' => ['col' => 'BU', 'row' => 13],
        ],
        'others' => [
           
        ],
    ],

    'block' => [
        [   // row1
            'B' => ['field' => 'location', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>3]],
            'F' => ['field' => 'ninusi1_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>5, 'h'=>3]],
            'K' => ['field' => 'hinmei_nm', 'type' => $exp::DATA_STRING],
            'W' => ['field' => 'kikaku', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>6, 'h'=>3]],
            'AC' => ['field' => 'lot1', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>3]],
            'AG' => ['field' => 'lot2', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>3]],
            'AK' => ['field' => 'lot3', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>3]],
            'AO' => ['field' => 'case_su', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>1]],
        ],
        [   // row2
            'AS' => ['field' => 'su', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>1]],
        ],
        [ // row 3
            'K' => ['field' => 'hinmei_cd', 'type' => $exp::DATA_STRING],
            'AO' => ['field' => 'hasu', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>1]],
        ]
    ]
];