<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'AU', 'row' => 56],
                'size' => 43
            ],
            'height' => 56,
        ],
        'groupBy' => [],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'AU', 'row' => 13],
            'mergeCells' => [
                ['col' => 'A', 'row' => 2, 'w' => 45, 'h' => 1],
                ['col' => 'A', 'row' => 4, 'w' => 45, 'h' => 1],
                ['col' => 'A', 'row' => 13, 'w' => 18, 'h' => 1],
                ['col' => 'S', 'row' => 13, 'w' => 5, 'h' => 1],
                ['col' => 'X', 'row' => 13, 'w' => 6, 'h' => 1],
                ['col' => 'AD', 'row' => 13, 'w' => 5, 'h' => 1],
                ['col' => 'AI', 'row' => 13, 'w' => 5, 'h' => 1],
                ['col' => 'AN', 'row' => 13, 'w' => 6, 'h' => 1],
                ['col' => 'B', 'row' => 9, 'w' => 15, 'h' => 1]
            ],
            'others' => [
                ['col' => 'B', 'row' => 9, 'value' => function($row) {
                    if(!empty($row[0])) {
                        return $row[0]->hachaku_nm;
                    }
                }],
                [
                    'col' => 'A', 'row' => 4, 'value' => function($row) {
                        if(!empty($row[0])) {
                            return \App\Helpers\Formatter::datetime($row[0]->haitatu_dt, 'Y/m/d');
                        }  
                    }
                ]
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 14],
            'end' => ['col' => 'AU', 'row' => 14],
        ],

    ],
    'block' => [
        [
            'A' => ['field' => 'hinmei_nm'],
            'R' => ['field' => 'syubetu_nm'],
            'S' => ['field' => 'su', 'mergeCells' => ['w'=> 5, 'h'=>1]],
            'X' => ['field' => 'field_no7', 'mergeCells' => ['w'=> 6, 'h'=>1]],
            'AD' => ['field' => 'tyukei_kin', 'mergeCells' => ['w'=> 5, 'h'=>1]],
            'AI' => ['field' => 'seikyu_kin_tax', 'mergeCells' => ['w'=> 5, 'h'=>1]],
            'AN' => ['field' => 'field_no10', 'mergeCells' => ['w'=> 6, 'h'=>1]]
        ]
    ],
];
