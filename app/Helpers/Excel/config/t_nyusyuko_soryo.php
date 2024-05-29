<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
$repo = new \App\Http\Repositories\Picking\SoryoRepository;

return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'BU', 'row' => 100],
                'size' => 30
            ],
            'height' => 100,
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'BU', 'row' => 10],
            'mergeCells' => [
                ['col' => 'AD', 'row' => 2, 'w' => 23, 'h' => 3],
                ['col' => 'BM', 'row' => 4, 'w' => 6, 'h' => 1],
                ['col' => 'BS', 'row' => 4, 'w' => 3, 'h' => 1],
                ['col' => 'B', 'row' => 5, 'w' => 7, 'h' => 2],
                ['col' => 'I', 'row' => 5, 'w' => 8, 'h' => 2],
                ['col' => 'E', 'row' => 7, 'w' => 23, 'h' => 1],
                ['col' => 'B', 'row' => 8, 'w' => 5, 'h' => 2],
                ['col' => 'G', 'row' => 8, 'w' => 5, 'h' => 2],
                ['col' => 'L', 'row' => 8, 'w' => 16, 'h' => 3],
                ['col' => 'AB', 'row' => 8, 'w' => 12, 'h' => 2],
                ['col' => 'AN', 'row' => 8, 'w' => 3, 'h' => 2],
                ['col' => 'AQ', 'row' => 8, 'w' => 3, 'h' => 2],
                ['col' => 'AT', 'row' => 8, 'w' => 3, 'h' => 2],
                ['col' => 'AW', 'row' => 8, 'w' => 4, 'h' => 3],
                ['col' => 'BA', 'row' => 8, 'w' => 2, 'h' => 2],
                ['col' => 'BC', 'row' => 9, 'w' => 4, 'h' => 2],
                ['col' => 'BG', 'row' => 8, 'w' => 4, 'h' => 3],
                ['col' => 'BK', 'row' => 8, 'w' => 2, 'h' => 2],
                ['col' => 'BM', 'row' => 9, 'w' => 4, 'h' => 2],
                ['col' => 'BQ', 'row' => 8, 'w' => 5, 'h' => 2],
            ],
            'others' => [
                ['col' => 'I', 'row' => 5, 'type' => $exp::DATA_DATETIME, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? data_get($first, 'kisan_dt') : null;
                }],
                ['col' => 'C', 'row' => 7, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? data_get($first, 'bumon_cd') : null;
                }],
                ['col' => 'E', 'row' => 7, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? data_get($first, 'bumon_nm') : null;
                }],
                ['col' => 'BM', 'row' => 4, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'BS', 'row' => 4, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE],

                ['col' => 'BG', 'row' => 8, 'type' => $exp::DATA_STRING, 'value' => function($page) use($repo){
                    $displayZaiko = in_array(
                        $repo::EXP_PRINT_OTHER_CSV_DISPLAY_ZAIKO,
                        data_get(request()->all(), 'exp.print_other', [])
                    );

                    if (!$displayZaiko) {
                        return ' ';
                    }
                }],

                ['col' => 'BK', 'row' => 8, 'type' => $exp::DATA_STRING, 'value' => function($page) use($repo){
                    $displayZaiko = in_array(
                        $repo::EXP_PRINT_OTHER_CSV_DISPLAY_ZAIKO,
                        data_get(request()->all(), 'exp.print_other', [])
                    );

                    if (!$displayZaiko) {
                        return ' ';
                    }
                }],
                ['col' => 'BM', 'row' => 9, 'type' => $exp::DATA_STRING, 'value' => function($page) use($repo){
                    $displayZaiko = in_array(
                        $repo::EXP_PRINT_OTHER_CSV_DISPLAY_ZAIKO,
                        data_get(request()->all(), 'exp.print_other', [])
                    );

                    if (!$displayZaiko) {
                        return ' ';
                    }
                }],
              
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
            'B' => ['field' => 'location', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>5, 'h'=>3]],
            'G' => ['field' => 'ninusi1_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>5, 'h'=>3]],
            'L' => ['field' => 'hinmei_nm', 'type' => $exp::DATA_STRING],
            'AB' => ['field' => 'kikaku', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>12, 'h'=>3]],
            'AN' => ['field' => 'lot1', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>3, 'h'=>3]],
            'AQ' => ['field' => 'lot2', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>3, 'h'=>3]],
            'AT' => ['field' => 'lot3', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>3, 'h'=>3]],
            'AW' => ['field' => 'case_su', 'type' => $exp::DATA_STRING],
            'BG' => ['field' => 't_zaiko__case_su', 'type' => $exp::DATA_STRING],
        ],
        [   // row2
            'BC' => ['field' => 'su', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>2]],
            'BM' => ['field' => 't_zaiko__su', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>4, 'h'=>2]],
        ],
        [ // row 3
            'L' => ['field' => 'hinmei_cd', 'type' => $exp::DATA_STRING],
            'AW' => ['field' => 'hasu', 'type' => $exp::DATA_STRING],
            'BA' => ['field' => 'tani_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>2, 'h'=>1]],
            'BG' => ['field' => 't_zaiko__hasu', 'type' => $exp::DATA_STRING],
            'BK' => ['field' => 'case_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>2, 'h'=>1]],
        ]
    ]
];