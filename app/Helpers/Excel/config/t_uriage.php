<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'size' => 13,
            ],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'BJ', 'row' => 9],
            'mergeCells' => [
                ['col' => 'BA', 'row' => 3, 'w' => 7, 'h' => 1],//current_time
                ['col' => 'BH', 'row' => 3, 'w' => 3, 'h' => 1],//page_no

                ['col' => 'AH', 'row' => 6, 'w' => 3, 'h' => 1],
                ['col' => 'AL', 'row' => 6, 'w' => 3, 'h' => 1],
                ['col' => 'AP', 'row' => 6, 'w' => 3, 'h' => 1],
                ['col' => 'AT', 'row' => 6, 'w' => 3, 'h' => 1],
                ['col' => 'AX', 'row' => 6, 'w' => 3, 'h' => 1],

                ['col' => 'AH', 'row' => 7, 'w' => 3, 'h' => 1],
                ['col' => 'AL', 'row' => 7, 'w' => 3, 'h' => 1],
                ['col' => 'AP', 'row' => 7, 'w' => 3, 'h' => 1],

                ['col' => 'AH', 'row' => 8, 'w' => 3, 'h' => 1],
                ['col' => 'AL', 'row' => 8, 'w' => 3, 'h' => 1],
                ['col' => 'AP', 'row' => 8, 'w' => 3, 'h' => 1],
                // ['col' => 'AT', 'row' => 8, 'w' => 3, 'h' => 1],
                ['col' => 'AX', 'row' => 8, 'w' => 3, 'h' => 1],

                ['col' => 'AH', 'row' => 9, 'w' => 3, 'h' => 1],
                ['col' => 'AL', 'row' => 9, 'w' => 3, 'h' => 1],
            ],
            'others' => [
                ['col' => 'D', 'row' => 4, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    return data_get($page, "0.bumon_cd", '');
                }],
                ['col' => 'E', 'row' => 4, 'value' => function($page) {
                    return data_get($page, "0.bumon_nm", '');
                }],
                ['col' => 'BA', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'BH', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 10],
            'end' => ['col' => 'BJ', 'row' => 13],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'ninusi_cd', 'mergeCells' => ['w'=>2, 'h'=>1]],//荷主CD	ninusi_cd
            'C' => ['field' => 'ninusi_ryaku_nm'],//荷主マスタ.略称	ninusi_ryaku_nm
            'K' => ['field' => 'uriage_den_no'],//売上NO  伝票NO	uriage_den_no
            'L' => ['field' => 'unso_dt', 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w'=>4, 'h'=>1]],//運送日   unso_dt
            'P' => ['field' => 'syaban', 'mergeCells' => ['w'=>2, 'h'=>1]],//車番  syaban
            'R' => ['field' => 'yousya_cd', 'mergeCells' => ['w'=>2, 'h'=>1]],//庸車先  庸車先CD   yousya_cd
            'T' => ['field' => 'yousya1_nm'],//庸車先  庸車先マスタ.庸車名1   yousya1_nm
            'AB' => ['field' => 'jyomuin_cd'],//運転者    運転者CD	jyomuin_cd
            'AC' => ['field' => 'jyomuin_nm'],//運転者    乗務員名	jyomuin_nm
            'AH' => ['value' => function ($row) {
                return \App\Helpers\Formatter::number(data_get($row, 'su')) . data_get($row, 'tani_nm');
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 3, 'h' => 1]],//数量	su
            'AL' => ['field' => 'unchin_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//基本運賃   unchin_kin
            'AP' => ['field' => 'tyukei_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//中継料    tyukei_kin
            'AT' => ['field' => 'yosya_tukoryo_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//通行料等   通行料 yosya_tukoryo_kin
            'AX' => ['field' => 'tesuryo_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//手数料  tesuryo_kin
            'BG' => ['field' => 'syuka_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//集荷料    syuka_kin
            //未使用
            //'BB' => ['field' => '荷役料', 'mergeCells' => ['w'=>3, 'h'=>1]],//荷役料
        ],
        [   // row2
            'A' => ['field' => 'hatuti_cd', 'mergeCells' => ['w'=>2, 'h'=>1]],//発地CD	ninusi_cd
            'C' => ['field' => 'hatuti_nm'],//発着地マスタ.名称	hachaku_nm
            'AL' => ['field' => 'yosya_tyukei_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//庸車料   yosya_tyukei_kin
            'AP' => ['field' => 'tukoryo_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//通行料等 tukoryo_kin
        ],
        [   // row3
            'A' => ['field' => 'hachaku_cd', 'mergeCells' => ['w'=>2, 'h'=>1]],//発着地CD	hachaku_cd
            'C' => ['field' => 'hachaku_nm'],//発着地マスタ.名称	hachaku_nm
            'R' => ['field' => 'hinmoku_cd', 'mergeCells' => ['w'=>2, 'h'=>1]],//品目    品目CD    hinmoku_cd
            'T' => ['field' => 'hinmoku_nm'],//品目    品目マスタ.名称	hinmoku_cd
            'AP' => ['field' => 'unten_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//車両金額   運転者金額   unten_kin
            'AT' => ['field' => 'unten_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],//運転者金額    運転者金額	unten_kin
            'BB' => ['field' => 'yousya_sime_dt', 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w'=>2, 'h'=>1]],//締日  支払締日    yousya_sime_dt
            'BD' => ['field' => 'seikyu_keijyo_dt', 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w'=>3, 'h'=>1]],//請求計上    請求計上日   seikyu_keijyo_dt
            'BH' => ['field' => 'seikyu_sime_dt', 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w'=>2, 'h'=>1]],//締日    請求締日    seikyu_sime_dt
            //未使用
            //'AH' => ['field' => '運賃単価', 'mergeCells' => ['w'=>3, 'h'=>1]],//運賃単価
            //'AL' => ['field' => '運賃消費税', 'mergeCells' => ['w'=>3, 'h'=>1]],//運賃消費税
            //'AX' => ['field' => '支払計上', 'mergeCells' => ['w'=>3, 'h'=>1]],//支払計上
        ],
        [   // row4
            'C' => ['field' => 'add_tanto_nm'],//入力担当名	add_tanto_nm
            'R' => ['field' => 'hinmei_cd', 'mergeCells' => ['w'=>2, 'h'=>1]],//品名    品目CD    hinmei_cd
            'T' => ['field' => 'hinmei_nm'],//品名    品名マスタ.名称	hinmei_nm
            'BG' => ['field' => 'nipou_dt', 'type' => $exp::DATA_DATETIME, 'mergeCells' => ['w'=>3, 'h'=>1]],//日報日  nipou_dt
            //未使用
            //'AH' => ['field' => '庸車単価', 'mergeCells' => ['w'=>3, 'h'=>1]],//庸車単価
            //'AL' => ['field' => '支払消費税', 'mergeCells' => ['w'=>3, 'h'=>1]],//支払消費税
        ],
    ],
];
