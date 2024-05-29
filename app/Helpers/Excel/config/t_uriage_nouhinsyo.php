<?php
$exp = new \App\Helpers\Excel\XlsNouhinsyou();
return [
    'base' => [
        'template' => [
            'page' => [
                $exp::FORM_1 => $form = [
                    'start' => ['col' => 'A', 'row' => 1],
                    'end' => ['col' => 'T', 'row' => 28],
                ],
                $exp::FORM_2 => $form,
                $exp::FORM_3 => $form,
                $exp::FORM_4 => $form,
            ],
            'size' => 5,
            'height' => 35,
        ],
        'header' => [
            $exp::FORM_1 => $header = [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'T', 'row' => 15],
                'mergeCells' => [
                    //タイトル
                    ['col' => 'H', 'row' => 2, 'w' => 6, 'h' => 2],
                    //配達日
                    ['col' => 'A', 'row' => 3, 'w' => 3, 'h' => 1],
                    ['col' => 'D', 'row' => 3, 'w' => 2, 'h' => 1],
                    //御依頼主
                    ['col' => 'A', 'row' => 13, 'w' => 3, 'h' => 1],
                    ['col' => 'D', 'row' => 13, 'w' => 6, 'h' => 1],
                    //
                    ['col' => 'L', 'row' => 13, 'w' => 6, 'h' => 1],
                    //集荷日
                    //['col' => 'S', 'row' => 13, 'w' => 1, 'h' => 1],
                    //['col' => 'T', 'row' => 13, 'w' => 1, 'h' => 1],

                    ['col' => 'A', 'row' => 15, 'w' => 8, 'h' => 1],//品名
                    ['col' => 'I', 'row' => 15, 'w' => 3, 'h' => 1],//数量
                    ['col' => 'L', 'row' => 15, 'w' => 9, 'h' => 1],//備考

                    ['col' => 'G', 'row' => 21, 'w' => 2, 'h' => 2],//合計
                    ['col' => 'I', 'row' => 21, 'w' => 2, 'h' => 2],//合計

                    ['col' => 'G', 'row' => 25, 'w' => 2, 'h' => 1],//車番
                    ['col' => 'G', 'row' => 26, 'w' => 2, 'h' => 2],//車番
                    ['col' => 'I', 'row' => 25, 'w' => 7, 'h' => 1],//運転者
                    ['col' => 'I', 'row' => 26, 'w' => 7, 'h' => 2],//運転者

                    //['col' => 'Q', 'row' => 25, 'w' => 4, 'h' => 1],//愛知県半田市潮干町２番地の３
                ],
                'others' => [
                    'title' => ['col' => 'H', 'row' => 2, 'value' => 'タイトル'],
                    [// 送り状番号
                        'col' => 'S', 'row' => 1,
                        'value' => function ($page) {
                            $first = $page->first();
                            return "No.           " . data_get($first, "okurijyo_no", '');
                        }
                    ],
                    [// 配達日
                        'col' => 'D', 'row' => 3, 'type' => $exp::DATA_DATETIME,
                        'value' => function ($page) {
                            $first = $page->first();
                            return data_get($first, "haitatu_dt");
                        }
                    ],

                    // 荷受人
                    ['col' => 'C', 'row' => 6, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hatuti_cd", '') == 1) return '';
                        return data_get($first, "hatuti_jyusyo1_nm", '');
                    }],
                    ['col' => 'C', 'row' => 7, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hatuti_cd", '') == 1) return '';
                        return data_get($first, "hatuti_jyusyo2_nm", '');
                    }],
                    ['col' => 'C', 'row' => 8, 'value' => function ($page) {
                        $first = $page->first();
                        return data_get($first, "hatuti_nm", '');
                    }, 'type' => $exp::DATA_STRING],
                    ['col' => 'C', 'row' => 9, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hatuti_cd", '') == 1) $text = '';
                        else $text = data_get($first, "hatuti_atena", '');
                        return empty($text) ? '' : ($text . '御中');
                    }],
                    ['col' => 'C', 'row' => 10, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hatuti_cd", '') == 1) return '';
                        return 'TEL: ' . data_get($first, "hatuti_tel", '');
                    }],

                    // 荷送人
                    ['col' => 'N', 'row' => 6, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hachaku_cd", '') == 1) return '';
                        return data_get($first, "hachaku_jyusyo1_nm", '');
                    }],
                    ['col' => 'N', 'row' => 7, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hachaku_cd", '') == 1) return '';
                        return data_get($first, "hachaku_jyusyo2_nm", '');
                    }],
                    ['col' => 'N', 'row' => 8, 'value' => function ($page) {
                        $first = $page->first();
                        return data_get($first, "hachaku_nm", '');
                    }, 'type' => $exp::DATA_STRING],
                    ['col' => 'N', 'row' => 9, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hachaku_cd", '') == 1) $text = '';
                        else $text = data_get($first, "hachaku_atena", '');
                        return empty($text) ? '' : ($text.'御中');
                    }],
                    ['col' => 'N', 'row' => 10, 'value' => function ($page) {
                        $first = $page->first();
                        if (data_get($first, "hachaku_cd", '') == 1) return '';
                        return 'TEL: ' . data_get($first, "hachaku_tel", '');
                    }],

                    [// 御依頼人
                        'col' => 'D', 'row' => 13,
                        'value' => function ($page) {
                            $first = $page->first();
                            $filtered = collect([
                                data_get($first, "ninusi_ninusi1_nm"),
                                data_get($first, "ninusi_ninusi2_nm"),
                            ])->filter(function ($value) {
                                return strlen($value);
                            });
                            return implode("\n", $filtered->toArray());
                        }
                    ],
                    [// 集荷日
                        'col' => 'T', 'row' => 13, 'type' => $exp::DATA_DATETIME,
                        'value' => function ($page) {
                            $first = $page->first();
                            return data_get($first, "syuka_dt");
                        }
                    ],

                    [// 合計
                        'col' => 'I', 'row' => 21,
                        'value' => function ($page) {
                            return $page->sum('su');
                        }
                    ],

                    [// 車番
                        'col' => 'G', 'row' => 26,
                        'value' => function ($page) {
                            $first = $page->first();
                            return data_get($first, "syaban");
                        }
                    ],
                    [// 運転者
                        'col' => 'I', 'row' => 26,
                        'value' => function ($page) {
                            $first = $page->first();
                            return data_get($first, "jyomuin_nm");
                        }
                    ],

                ],
            ],
            $exp::FORM_2 => $header,
            $exp::FORM_3 => $header,
            $exp::FORM_4 => $header,
        ],
        'stamp' => [ // 受領印
            $exp::FORM_1 => $stamp = [
                'start' => ['col' => 'A', 'row' => 30],
                'end' => ['col' => 'D', 'row' => 33],
                'mergeCells' => [
                    ['col' => 'A', 'row' => 24, 'w' => 4, 'h' => 1],
                ],
                'toCell' => ['col' => 'A', 'row' => 24],
            ],
            $exp::FORM_2 => $stamp,
            $exp::FORM_3 => $stamp,
            $exp::FORM_4 => $stamp,
        ],
        'block' => [
            $exp::FORM_1 => $block = [
                'start' => ['col' => 'A', 'row' => 16],
                'end' => ['col' => 'T', 'row' => 16],
            ],
            $exp::FORM_2 => $block,
            $exp::FORM_3 => $block,
            $exp::FORM_4 => $block,
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row2
            'A' => ['field' => 'hinmei_nm', 'mergeCells' => ['w'=> 8, 'h' => 1]],//品名  m_hinmei.hinmei_nm
            'I' => ['field' => 'su', 'mergeCells' => ['w'=> 2, 'h' => 1]],//致量
            'K' => ['field' => 'tani_nm'],//致量
            'L' => ['field' => 'biko', 'mergeCells' => ['w'=> 9, 'h' => 1]],//備考
        ],
    ],
];
