<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();

return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'P', 'row' => 89],
                'size' => 16
            ],
            'height' => 89,
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'P', 'row' => 9],
            'mergeCells' => [
                ['col' => 'G', 'row' => 1, 'w' => 3, 'h' => 3],
                ['col' => 'B', 'row' => 7, 'w' => 1, 'h' => 3],
                ['col' => 'C', 'row' => 7, 'w' => 6, 'h' => 3],
                ['col' => 'I', 'row' => 7, 'w' => 6, 'h' => 1],
                ['col' => 'I', 'row' => 9, 'w' => 6, 'h' => 1],
                ['col' => 'O', 'row' => 7, 'w' => 2, 'h' => 1],

            ],
            'others' => [
                ['col' => 'A', 'row' => 4, 'type' => $exp::DATA_CLOSURE, 'value' => function($page) {
                    $field = data_get($this->sagyoRepository->getExportInjiGroupOpts(), request('exp.inji_group') . '.field');
                    return App\Helpers\Formatter::dateJP(data_get($page, "0." . $field, ''), 'y年m月d日(w)');
                }],
                ['col' => 'A', 'row' => 6, 'type' => $exp::DATA_CLOSURE, 'value' => function($page) {
                    $field = data_get($this->sagyoRepository->getExportTyohyokbnOpts(), data_get($page, "0.tyohyokbn", '') . '.fieldConcat');
                    return data_get($page, "0." . $field, '');
                }],

                ['col' => 'C', 'row' => 6, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return request('exp.tyohyokbn_modal.' . data_get($page, "0.tyohyokbn", '') .'.under_title');
                }],
                ['col' => 'M', 'row' => 3, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return request('exp.tyohyokbn_modal.' . data_get($page, "0.tyohyokbn", '') .'.upper');
                }],
                ['col' => 'M', 'row' => 4, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return request('exp.tyohyokbn_modal.' . data_get($page, "0.tyohyokbn", '') .'.middle');
                }],
                ['col' => 'M', 'row' => 5, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return request('exp.tyohyokbn_modal.' . data_get($page, "0.tyohyokbn", '') .'.bottom');
                }],
                ['col' => 'N', 'row' => 2, 'value' => date('Y/m/d H:i')],
                ['col' => 'P', 'row' => 2, 'constVal' => $exp::VAL_PAGE_NO],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 10],
            'end' => ['col' => 'AX', 'row' => 14],
        ],
        'others' => [

        ]
    ],
    'block' => [
        0 => [

        ],
        1 => [   // row2
            'A' => ['field' => 'syaban'],
            'B' => ['field' => 'syuka_tm_formatted'],
            'C' => ['field' => 'hachaku_nm'],
            'H' => ['field' => 'haitatu_dt'],
            'I' => ['field' => 'hinmei_nm'],
            'O' => ['field' => 'su'],
            'P' => ['field' => 'tani_nm'],
        ],
        2 => [   // row3
            'A' => ['field' => 'ninusi_ryaku_nm', 'mergeCells' => ['w'=>1, 'h'=>2]],
            'H' => ['field' => 'jikoku_formatted'],
            'I' => ['field' => 'biko'],
        ],
        3 => [   // row 4
        ],
        4 => [   // row 5
        ],
    ],
];
