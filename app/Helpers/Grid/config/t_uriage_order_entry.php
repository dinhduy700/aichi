<?php 
return [
    [
        'field' => 'checkbox',
        'checkbox' => true,
        'width' => 50
    ],
    [
        'field' => 'bumon_cd',
        'title' => '部門CD',
        'editable' => true,
        'visible' => true,
        'width' => 200,
        'suggestion' => true,
        'suggestion_change' => [
            'bumon_cd',
            'bumon_nm', 
            'kana'
        ],
        'class' => 'not-pd-arrow th-60',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'bumon_nm'
        ],
        'link' => route('master.bumon.create')
    ],
    [
        'field' => 'bumon_nm',
        'title' => '受注部門名',
        'editable' => true,
        'width' => 200,
        'suggestion' => true,
        'suggestion_change' => [
            'bumon_cd',
            'bumon_nm',
            'kana'
        ],
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.bumon.create')
    ],
    [
        'field' => 'hatuti_cd',
        'title' => '発地CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hatuti_cd',
            'hatuti_nm',
            'kana'
        ], 
        'class' => 'not-pd-arrow th-60',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'hatuti_nm'
        ],
        'link' => route('master.hachaku.create')
    ],
    [
        'field' => 'hatuti_nm',
        'title' => '発地名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hatuti_cd',
            'hatuti_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.hachaku.create')
    ],
    [
        'field' => 'genkin_cd',
        'title' => 'CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'genkin_cd',
            'genkin_nm',
            'kana'
        ],
        // 'type' => 'select',
        // 'selections' => ["" => ""] +  configParam('options.t_uriage.genkin_cd'), 
        'class' => 'th-45 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'genkin_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'genkin_nm',
        'title' => '現金名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'genkin_cd',
            'genkin_nm',
            'kana'
        ], 
        'class' => 'not-pd-arrow th-60',
        'sortable' => true,
        'link' => route('master.meisyo.create')
        // 'copitable' => true
    ],
    [
        'field' => 'ninusi_cd',
        'title' => '荷主CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'ninusi_cd',
            'ninusi_nm',
            'kana'
        ], 
        'class' => 'not-pd-arrow th-60',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'ninusi_nm'
        ],
        'link' => route('master.ninusi.create'),
        'onchange' => 'onchangeNinusi'
    ],
    [
        'field' => 'ninusi_nm',
        'title' => '荷主名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'ninusi_cd',
            'ninusi_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.ninusi.create'),
        'onchange' => 'onchangeNinusi'
    ],

    [
        'field' => 'syuka_dt',
        'title' => '集荷日',
        'editable' => true,
        'type' => 'date',
        'class' => 'th-100 not-pd-arrow',
        'sortable' => true,
        'copitable' => true
    ],

    [
        'field' => 'haitatu_dt',
        'title' => '配達日',
        'editable' => true,
        'type' => 'date',
        'class' => 'th-100 not-pd-arrow',
        'sortable' => true,
        'copitable' => true
    ],
    [
        'field' => 'hachaku_cd',
        'title' => '着地CD',
        'editable' => true,
        'suggestion' => true,
        'sortable' => true,
        'suggestion_change' => [
            'hachaku_cd',
            'hachaku_nm',
            'kana'
        ], 
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'hachaku_nm'
        ],
        'link' => route('master.hachaku.create')
    ],
    [
        'field' => 'hachaku_nm',
        'title' => '着地名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hachaku_cd',
            'hachaku_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.hachaku.create')
    ],
    [
        'field' => 'su',
        'title' => '数量',
        'editable' => true,
        'class' => 'th-130',
        'copitable' => true,
        'type' => 'numberDecimal',
        'sortable' => true,
        'maxlength' => 10,
        'maxdecimal' => 3
    ],
    [
        'field' => 'hinmei_cd',
        'title' => '品名CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hinmei_cd',
            'hinmei_nm',
            'kana',
            'hinmoku_nm'
        ],
        'suggestion_hide' => [
            'hinmoku_nm'
        ],
        'suggestion_checkbox' => [
            'ninusi_cd' => '荷主CDをwhere条件にする'
        ],
        'class' => 'th-70 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'hinmoku_nm',
            'hinmei_nm'
        ],
        'link' => route('master.hinmei.create')
    ],
    [
        'field' => 'hinmoku_nm',
        'title' => '品目名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hinmoku_nm'
        ],
        'suggestion_checkbox' => [
            'ninusi_cd' => '荷主CDをwhere条件にする'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.hinmei.create')
    ],
    [
        'field' => 'hinmei_nm',
        'title' => '品名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hinmei_cd',
            'hinmei_nm',
            'kana',
            'hinmoku_nm'
        ],
        'suggestion_hide' => [
            'hinmoku_nm'
        ], 
        'suggestion_checkbox' => [
            'ninusi_cd' => '荷主CDをwhere条件にする'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.hinmei.create')
    ],
    [
        'field' => 'tani_cd',
        'title' => '単位CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'tani_cd',
            'tani_nm',
            'kana'
        ], 
        'class' => 'th-60 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'tani_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'tani_nm',
        'title' => '単位名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'tani_cd',
            'tani_nm',
            'kana'
        ], 
        'class' => 'th-70 not-pd-arrow',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'jyotai',
        'title' => '状態',
        'editable' => true,
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'maxlength' => 255
    ],
    [
        'field' => 'sitadori',
        'title' => '下取',
        'editable' => true,
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'maxlength' => 255
    ],

    [
        'field' => 'gyosya_cd',
        'title' => '業者CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'gyosya_cd',
            'gyosya_nm',
            'kana'
        ], 
        'class' => 'th-60 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'gyosya_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'gyosya_nm',
        'title' => '業者名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'gyosya_cd',
            'gyosya_nm',
            'kana'
        ], 
        'class' => 'th-120 not-pd-arrow',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'tyuki',
        'title' => '配達注記',
        'editable' => true,
        'class' => 'size-L ime-mode',
        'copitable' => true,
        'sortable' => true
    ],
    [
        'field' => 'tanka_kbn',
        'title' => '単価区分',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'tanka_kbn',
            'tanka_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'tanka_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'tanka_nm',
        'title' => '単価名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'tanka_kbn',
            'tanka_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'seikyu_tanka',
        'title' => '請求単価',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberDecimal',
        'maxlength' => 9
    ],
    [
        'field' => 'unchin_kin',
        'title' => '基本運賃',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],
    [
        'field' => 'tyukei_kin',
        'title' => '中継料',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],
    [
        'field' => 'tukoryo_kin',
        'title' => '通行料等',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],
    [
        'field' => 'syuka_kin',
        'title' => '集荷料',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],
    [
        'field' => 'tesuryo_kin',
        'title' => '手数料',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],

    [
        'field' => 'biko_cd',
        'title' => '備考CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'biko_cd',
            'biko',
            'kana'
        ], 
        'class' => 'th-60 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'biko'
        ],
        'link' => route('master.biko.create')
    ],
    [
        'field' => 'biko',
        'title' => '備考名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'biko_cd',
            'biko',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.biko.create')
    ],
    [
        'field' => 'syaryo_kin',
        'title' => '車両金額',
        'editable' => true,
        'sortable' => true,
        'class' => 'th-100 not-pd-arrow',
        'type' => 'number',
        'maxlength' => 7
    ],
    [
        'field' => 'unten_kin',
        'title' => '運転者金額',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],

    [
        'field' => 'unchin_mikakutei_kbn',
        'title' => '運賃確定区分',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'unchin_mikakutei_kbn',
            'unchin_mikakutei_nm',
            'kana'
        ],
        // 'type' => 'select',
        // 'selections' => ["" => ""] + configParam('options.t_uriage.unchin_mikakutei_kbn'), 
        'class' => 'th-45 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'unchin_mikakutei_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'unchin_mikakutei_nm',
        'title' => '運賃確定名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'unchin_mikakutei_kbn',
            'unchin_mikakutei_nm',
            'kana'
        ], 
        'class' => 'th-110 not-pd-arrow',
        'sortable' => true,
        'link' => route('master.meisyo.create')
        // 'copitable' => true
    ],
    [
        'field' => 'yousya_cd',
        'title' => '庸車先CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'yousya_cd',
            'yousya_nm',
            'kana'
        ], 
        'class' => 'th-60 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'yousya_nm'
        ],
        'link' => route('master.yousya.create')
    ],
    [
        'field' => 'yousya_nm',
        'title' => '庸車先名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'yousya_cd',
            'yousya_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.yousya.create')
    ],
    [
        'field' => 'yosya_tyukei_kin',
        'title' => '庸車料',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],
    [
        'field' => 'yosya_tukoryo_kin',
        'title' => '庸車通行料等',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'maxlength' => 7
    ],
    [
        'field' => 'okurijyo_no',
        'title' => '送り状番号',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'maxlength' => 255
    ],
    [
        'field' => 'jyutyu_kbn',
        'title' => '受注区分',
        'editable' => true,
        'class' => 'th-45 not-pd-arrow',
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'jyutyu_kbn',
            'jyutyu_nm',
            'kana'
        ],
        'copyListHidden' => [
            'jyutyu_nm'
        ],
        'sortable' => true,
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'jyutyu_nm',
        'title' => '受注区分名',
        'editable' => true,
        'class' => 'th-110 not-pd-arrow',
        // 'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'jyutyu_kbn',
            'jyutyu_nm',
            'kana'
        ],
        'sortable' => true,
        'link' => route('master.meisyo.create')
    ],
    [
        'field' => 'kaisyu_dt',
        'title' => '回収日',
        'editable' => true,
        'class' => 'size-L',
        'type' => 'date',
        'copitable' => true,
        'sortable' => true,
    ],
    [
        'field' => 'kaisyu_kin',
        'title' => '回収金額',
        'editable' => true,
        'class' => 'th-100 not-pd-arrow',
        'copitable' => true,
        'sortable' => true,
        'type' => 'number',
        'maxlength' => 7
    ],
    // [
    //     'field' => 'tukoryo_kin',
    //     'title' => '通行料等',
    //     'editable' => true,
    //     'class' => 'size-L',
    //     'copitable' => true,
    //     'sortable' => true,
    // ],
    [
        'field' => 'add_tanto_cd',
        'title' => '入力担当CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'add_tanto_cd',
            'add_tanto_nm',
            'kana'
        ], 
        'class' => 'th-60 not-pd-arrow',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'add_tanto_nm'
        ],
        'link' => route('master.jyomuin.create')
    ],
    [
        'field' => 'add_tanto_nm',
        'title' => '入力担当者名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'add_tanto_cd',
            'add_tanto_nm',
            'kana'
        ], 
        'class' => 'th-135 not-pd-arrow',
        'sortable' => true,
        // 'copitable' => true,
        'link' => route('master.jyomuin.create')
    ],
    [
        'field' => 'uriage_den_no',
        'title' => '売上番号',
        'editable' => false,
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => false,
        'align' => 'right',
        'nocreate' => true
    ],
    [
        'field' => 'haitatu_tel',
        'title' => '配達TEL',
        'editable' => true,
        'copitable' => true, 
        'sortable' => true,
        'maxlength' => 255
    ]
];