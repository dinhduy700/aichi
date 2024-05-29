<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'O', 'row' => 13],
                'size' => 0
            ],
            'height' => 13,
        ],
        'groupBy' => [],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'AU', 'row' => 13],
            'mergeCells' => [
                ['col' => 'L', 'row' => 1, 'w' => 4, 'h' => 1],
                ['col' => 'A', 'row' => 2, 'w' => 2, 'h' => 1],
                ['col' => 'C', 'row' => 2, 'w' => 3, 'h' => 1],
                ['col' => 'I', 'row' => 2, 'w' => 3, 'h' => 1],
                ['col' => 'L', 'row' => 2, 'w' => 4, 'h' => 1],
                ['col' => 'C', 'row' => 3, 'w' => 11, 'h' => 1],
                ['col' => 'C', 'row' => 4, 'w' => 7, 'h' => 1],
                ['col' => 'L', 'row' => 4, 'w' => 2, 'h' => 1],
                ['col' => 'N', 'row' => 4, 'w' => 2, 'h' => 1],
                ['col' => 'A', 'row' => 5, 'w' => 2, 'h' => 1],
                ['col' => 'C', 'row' => 5, 'w' => 9, 'h' => 1],
            ],
            'others' => [
                [
                    'col' => 'L', 'row' => 1, 'value' => function($row) {
                        return $row->jyomuin_nm;
                    }
                ],
                [
                    'col' => 'C', 'row' => 2, 'value' => function($row) {
                        return \App\Helpers\Formatter::datetime($row->haitatu_dt, 'm/d');
                    }
                ],
                [
                    'col' => 'L', 'row' => 2, 'value' => function($row) {
                        return \App\Helpers\Formatter::datetime($row->syuka_dt, 'm/d');
                    }
                ],
                [
                    'col' => 'C', 'row' => 3, 'value' =>  function($row) {
                        return $row->hachaku_nm;
                    }
                ],
                [
                    'col' => 'C', 'row' => 4, 'value' =>  function($row) {
                        return $row->hinmoku_nm;
                    }
                ],
                [
                    'col' => 'L', 'row' => 4, 'value' => function($row) {
                        return numberFormat($row->su, -1);
                    }
                ],
                [
                    'col' => 'C', 'row' => 5, 'value' =>  function($row) {
                        return $row->hinmei_nm;
                    }
                ],
                [
                    'col' => 'C', 'row' => 6, 'value' =>  function($row) {
                        return '備考'.$row->biko;
                    }
                ]
            ],
        ],
        'block' => [
        ],

    ],
    'block' => [
    ],
];
