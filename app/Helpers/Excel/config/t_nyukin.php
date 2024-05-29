<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'AD', 'row' => 46],
                'size' => 14,
            ],
            'summary' => [
                'shokei' => [
                    'start' => ['col' => 'A', 'row' => 48],
                    'end' => ['col' => 'AD', 'row' => 49],
                ],
                'gokei' => [
                    'start' => ['col' => 'A', 'row' => 50],
                    'end' => ['col' => 'AD', 'row' => 51],
                ]
            ],
            'height' => 52,
        ],
        'summary' => [
            'shokei' => [
                //【　小　計　】
                ['col' => 'D', 'row' => 2, 'mergeCells' => ['w' => 2, 'h' => 1]],
                //入金合計
                ['col' => 'F', 'row' => 2, 'value' => function ($row) {
                    return $row->sum('nyukin_gokei');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //現　金
                ['col' => 'J', 'row' => 1, 'value' => function ($row) {
                    return $row->sum('genkin_kin');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //振　込
                ['col' => 'N', 'row' => 1, 'value' => function ($row) {
                    return $row->sum('furikomi_kin');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //振込手数料
                ['col' => 'R', 'row' => 1, 'value' => function ($row) {
                    return $row->sum('furikomi_tesuryo_kin');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //手形
                ['col' => 'V', 'row' => 1, 'value' => function ($row) {
                    return $row->sum('tegata_kin');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],

                //相　殺
                ['col' => 'J', 'row' => 2, 'value' => function ($row) {
                    return $row->sum('sousai_kin');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //値　引
                ['col' => 'N', 'row' => 2, 'value' => function ($row) {
                    return $row->sum('nebiki_kin');
                }, 'type' => $exp::DATA_CLOSURE,'mergeCells' => ['w' => 4, 'h' => 1]],
                //その他
                ['col' => 'R', 'row' => 2, 'value' => function ($row) {
                    return $row->sum('sonota_nyu_kin');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                ['col' => 'V', 'row' => 2, 'mergeCells' => ['w' => 4, 'h' => 1]],
            ],
            'gokei' => [
                //【　合　計　】
                ['col' => 'D', 'row' => 2, 'mergeCells' => ['w' => 2, 'h' => 1]],
                //入金合計
                ['col' => 'F', 'row' => 2, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('nyukin_gokei');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //現　金
                ['col' => 'J', 'row' => 1, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('genkin_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //振　込
                ['col' => 'N', 'row' => 1, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('furikomi_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //振込手数料
                ['col' => 'R', 'row' => 1, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('furikomi_tesuryo_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //手形
                ['col' => 'V', 'row' => 1, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('tegata_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //相　殺
                ['col' => 'J', 'row' => 2, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('sousai_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                //値　引
                ['col' => 'N', 'row' => 2, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('nebiki_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE,'mergeCells' => ['w' => 4, 'h' => 1]],
                //その他
                ['col' => 'R', 'row' => 2, 'value' => function ($row) {
                    return $row->sum(function ($collection) {
                        return $collection->sum('sonota_nyu_kin');
                    });
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 1]],
                ['col' => 'V', 'row' => 2, 'mergeCells' => ['w' => 4, 'h' => 1]],
            ],
        ],
        'groupBy' => ['nyukin_dt'],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'AD', 'row' => 8],
            'mergeCells' => [
                ['col' => 'L', 'row' => 2, 'w' => 10, 'h' => 3],//入金一覧表

                ['col' => 'D', 'row' => 8, 'w' => 2, 'h' => 1],//荷主
                ['col' => 'F', 'row' => 8, 'w' => 4, 'h' => 1],//入金合計

                ['col' => 'J', 'row' => 7, 'w' => 4, 'h' => 1],//現金
                ['col' => 'J', 'row' => 8, 'w' => 4, 'h' => 1],//相殺

                ['col' => 'N', 'row' => 7, 'w' => 4, 'h' => 1],//振込
                ['col' => 'N', 'row' => 8, 'w' => 4, 'h' => 1],//値引

                ['col' => 'R', 'row' => 7, 'w' => 4, 'h' => 1],//振込手数料
                ['col' => 'R', 'row' => 8, 'w' => 4, 'h' => 1],//その他

                ['col' => 'V', 'row' => 7, 'w' => 4, 'h' => 1],//手形
                ['col' => 'V', 'row' => 8, 'w' => 4, 'h' => 1],//手形期日

                ['col' => 'AB', 'row' => 8, 'w' => 2, 'h' => 1],//備考
            ],
            'others' => [
                ['col' => 'B', 'row' => 6, 'value' => function($row) {
                    if (request()->filled('exp.nyukin_dt_from') || request()->filled('exp.nyukin_dt_to')) {
                        return substr(request('exp.nyukin_dt_from'), 2) . '〜' . substr(request('exp.nyukin_dt_to'), 2);
                    }
                }, 'type' => $exp::DATA_CLOSURE],
                ['col' => 'AB', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'AC', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO],
            ],
        ],
        'block' => [
            'start' => ['col' => 'B', 'row' => 9],
            'end' => ['col' => 'AC', 'row' => 10],
        ],
        'others' => [

        ]
    ],
    'block' => [
        [   // row1
            'J' => ['field' => 'genkin_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//現金 t_nyukin.genkin_kin
            'N' => ['field' => 'furikomi_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//振込 t_nyukin.furikomi_kin
            'R' => ['field' => 'furikomi_tesuryo_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//振込手数料 t_nyukin.furikomi_tesuryo_kin
            'V' => ['field' => 'tegata_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//手形 t_nyukin.tegata_kin
            'AB' => ['field' => 'biko', 'mergeCells' => ['w' => 2, 'h' => 2]],//備考 t_nyukin.biko
        ],
        [   // row2
            'B' => ['field' => 'nyukin_dt', 'type' => $exp::DATA_DATETIME],//入金日	t_nyukin.nyukin_dt
            'C' => ['field' => 'nyukin_no'],//入金NO  t_nyukin.nyukin_no
            'D' => ['field' => 'ninusi_cd'],//荷主  t_nyukin.ninusi_cd,
            'E' => ['field' => 'ninusi1_nm'],//荷主
            'F' => ['field' => 'nyukin_gokei', 'type' => $exp::DATA_STRING, 'mergeCells' => ['w' => 4, 'h' => 1]],//入金合計 t_nyukin.nyukin_gokei
            'J' => ['field' => 'sousai_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//相殺 t_nyukin.sousai_kin
            'N' => ['field' => 'nebiki_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//値引 t_nyukin.nebiki_kin
            'R' => ['field' => 'sonota_nyu_kin', 'mergeCells' => ['w' => 4, 'h' => 1]],//その他入金 t_nyukin.sonota_nyu_kin
            'V' => ['field' => 'tegata_kijitu_kin', 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w' => 4, 'h' => 1]],//手形期日 t_nyukin.tegata_kijitu_kin
            'Z' => ['field' => 'seikyu_sime_dt', 'type' => $exp::DATA_DATETIME],//締日 t_nyukin.seikyu_sime_dt
            'AA' => ['field' => 'hikiate_simebi_dt', 'type' => $exp::DATA_DATETIME],//引当締日
        ],
    ],
];
