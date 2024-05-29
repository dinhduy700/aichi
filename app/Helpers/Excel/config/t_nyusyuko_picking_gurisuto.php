<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
$repo = new \App\Http\Repositories\Picking\PickingGurisutoRepository;

return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'BA', 'row' => 81],
                'size' => 24
            ],
            'height' => 81,
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'BA', 'row' => 9],
            'mergeCells' => [
                ['col' => 'R', 'row' => 1, 'w' => 20, 'h' => 3],
                ['col' => 'B', 'row' => 3, 'w' => 7, 'h' => 3],
                ['col' => 'AP', 'row' => 3, 'w' => 7, 'h' => 1],
                ['col' => 'AX', 'row' => 3, 'w' => 2, 'h' => 1],
                ['col' => 'I', 'row' => 3, 'w' => 7, 'h' => 3],
                ['col' => 'E', 'row' => 6, 'w' => 7, 'h' => 1],
                ['col' => 'B', 'row' => 7, 'w' => 9, 'h' => 2],
                ['col' => 'K', 'row' => 7, 'w' => 5, 'h' => 2],
                ['col' => 'P', 'row' => 7, 'w' => 7, 'h' => 1],
                ['col' => 'P', 'row' => 9, 'w' => 5, 'h' => 1],
                ['col' => 'Z', 'row' => 7, 'w' => 5, 'h' => 2],
                ['col' => 'AE', 'row' => 7, 'w' => 3, 'h' => 2],
                ['col' => 'AH', 'row' => 7, 'w' => 3, 'h' => 2],
                ['col' => 'AK', 'row' => 7, 'w' => 3, 'h' => 2],
                ['col' => 'AN', 'row' => 7, 'w' => 4, 'h' => 1],
                ['col' => 'AN', 'row' => 9, 'w' => 4, 'h' => 1],
                ['col' => 'AR', 'row' => 7, 'w' => 2, 'h' => 1],
                ['col' => 'AT', 'row' => 8, 'w' => 5, 'h' => 2],
                ['col' => 'AY', 'row' => 7, 'w' => 3, 'h' => 2],
            ],
            'others' => [
                ['col' => 'I', 'row' => 3, 'type' => $exp::DATA_DATETIME, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? data_get($first, 'kisan_dt'): null;
                }],
                ['col' => 'C', 'row' => 6, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? data_get($first, 'bumon_cd') : null;
                }],
                ['col' => 'E', 'row' => 6, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? data_get($first, 'bumon_nm') : null;
                }],
                ['col' => 'L', 'row' => 6, 'type' => $exp::DATA_STRING, 'value' => function($page) use($repo){
                    $first  = !empty($page) ? $page->first() : null;
                    $res    = !empty($first) ? '車番:' . data_get($first, 'syaban') : null;

                    $isSyaban = in_array(
                        $repo::EXP_PRINT_OTHER_SYABAN,
                        data_get(request()->all(), 'exp.print_other', [])
                    );
                   
                    return $isSyaban ? $res : null;
                }],
                ['col' => 'AP', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'AX', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE],
              
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 10],
            'end' => ['col' => 'BA', 'row' => 12],
        ],
        'others' => [
           
        ],
    ],
  
    'block' => [
        [   // row1
            'B' => ['field' => 'todokesaki_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>9, 'h'=>3]],
            'K' => ['field' => 'ninusi1_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>5, 'h'=>3]],
            'P' => ['field' => 'hinmei_nm', 'type' => $exp::DATA_STRING],
            'Z' => ['field' => 'kikaku', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>5, 'h'=>3]],
            'AE' => ['field' => 'lot1', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>3, 'h'=>3]],
            'AH' => ['field' => 'lot2', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>3, 'h'=>3]],
            'AK' => ['field' => 'lot3', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>3, 'h'=>3]],
            'AN' => ['field' => 'case_su', 'type' => $exp::DATA_STRING],
            'AT' => ['field' => 'su', 'type' => $exp::DATA_STRING],
        ],
        [   // row2
        ],
        [ // row 3
            'P' => ['field' => 'hinmei_cd', 'type' => $exp::DATA_STRING],
            'AN' => ['field' => 'hasu', 'type' => $exp::DATA_STRING],
            'AR' => ['field' => 'tani_nm', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w'=>2, 'h'=>1]],
        ]
    ]
];