<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'AT', 'row' => 37],
                'size' => 5,
            ],
            'height' => 37,
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'CG', 'row' => 17],
            'mergeCells' => [
                ['col' => 'G', 'row' => 2, 'w' => 5, 'h' => 2],
                ['col' => 'P', 'row' => 1, 'w' => 11, 'h' => 3],
                ['col' => 'D', 'row' => 2, 'w' => 3, 'h' => 2],
                ['col' => 'D', 'row' => 13, 'w' => 3, 'h' => 2],
                // ['col' => 'G', 'row' => 13, 'w' => 14, 'h' => 2],
                ['col' => 'X', 'row' => 13, 'w' => 8, 'h' => 2],
                ['col' => 'AG', 'row' => 13, 'w' => 3, 'h' => 2],
                ['col' => 'AJ', 'row' => 13, 'w' => 6, 'h' => 2],

                ['col' => 'D', 'row' => 16, 'w' => 14, 'h' => 2],
                ['col' => 'R', 'row' => 16, 'w' => 6, 'h' => 2],
                ['col' => 'X', 'row' => 16, 'w' => 18, 'h' => 2],
                ['col' => 'N', 'row' => 28, 'w' => 4, 'h' => 2],
                ['col' => 'R', 'row' => 28, 'w' => 6, 'h' => 2],

                ['col' => 'D', 'row' => 31, 'w' => 4, 'h' => 1],

                ['col' => 'N', 'row' => 32, 'w' => 4, 'h' => 2],
                ['col' => 'R', 'row' => 32, 'w' => 10, 'h' => 2],

                ['col' => 'N', 'row' => 34, 'w' => 4, 'h' => 2],
                ['col' => 'R', 'row' => 34, 'w' => 10, 'h' => 2],
            ],
            'others' => [
                ['col' => 'G', 'row' => 2, 'value' => function($list) {
                    if(!empty($list)) {
                        if(!empty($list->first())) {
                            return \App\Helpers\Formatter::dateJP($list->first()->nouhin_dt, \App\Helpers\Formatter::DT_SHORT_JP_NENGETUHI);
                        }
                    }
                    return '';
                }],
                [
                    'col' => 'AH', 'row' => 2, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? 'No. ' . $list->first()->okurijyo_no : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'F', 'row' => 6, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? $list->first()->haitatu_jyusyo1 .' '. $list->first()->haitatu_jyusyo2 : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'F', 'row' => 8, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? $list->first()->haitatu_atena .' '. $list->first()->todokesaki_nm : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'F', 'row' => 10, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? 'TEL:'.$list->first()->haitatu_tel : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'Z', 'row' => 6, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? $list->first()->hatuti_jyusyo1 .' '. $list->first()->hatuti_jyusyo2 : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'Z', 'row' => 8, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? $list->first()->hatuti_nm .' '. $list->first()->hatuti_atena : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'Z', 'row' => 10, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? 'TEL:'.$list->first()->hatuti_tel : ''; 
                        }
                        return '';
                    }
                ],

                [
                    'col' => 'R' , 'row' => 28, 'value' => function($list) {
                        if(!empty($list)) {
                            return numberFormat($list->sum('su'), -1); 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'N', 'row' => 34, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? $list->first()->syaban : ''; 
                        }
                        return '';
                    }
                ],
                [
                    'col' => 'R', 'row' => 34, 'value' => function($list) {
                        if(!empty($list)) {
                            return $list->first() ? $list->first()->jyomuin_nm : ''; 
                        }
                        return '';
                    }
                ]
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 18],
            'end' => ['col' => 'AT', 'row' => 19],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   
            'D' => [
                'value' => function($row) {
                    return $row->hinmei_cd .' '. $row->hinmei_nm;
                },
                'mergeCells' => ['w' => 14, 'h' => 2]
            ],
            'R' => [
                'value' => function($row) {
                    return numberFormat($row->su, -1);
                },
                'mergeCells' => ['w' => 4, 'h' => 2]
            ],
            'V' => [
                'field' => 'tani_nm',
                'mergeCells' => ['w' => 2, 'h' => 2]
            ],
            'X' => [
                'field' => 'biko',
                'mergeCells' => ['w' => 18, 'h' => 2]
            ]
        ],
        []
    ],
];
