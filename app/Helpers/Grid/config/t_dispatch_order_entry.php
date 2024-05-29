<?php 
return [
    'syaban' => [
        'field' => 'syaban',
        'title' => '車番',
        'editable' => true,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'url' => route('order.order_entry.dispatch_suggestion', 'syaban'),
        'class' => 'size-L',
        'width' => 70,
        'copitable' => true
    ],
    'syubetu_cd' => [
        'field' => 'syubetu_cd',
        'title' => '種別CD',
        'editable' => true,
        'width' => 70,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'syubetu_cd'),
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'syubetu_nm'
        ],
    ],
    'syubetu_nm' => [
        'field' => 'syubetu_nm',
        'title' => '種別名',
        'editable' => true,
        'width' => 140,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'syubetu_cd',
            'syubetu_nm',
            'kana'
        ],
        'class' => 'size-L',
        'sortable' => true,
        // 'link' => route('master.bumon.create')
    ],
    'bumon_cd' => [
        'field' => 'bumon_cd',
        'title' => '部門CD',
        'editable' => true,
        'width' => 65,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'bumon_cd'),
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'bumon_nm'
        ],
        'link' => route('master.bumon.create')
    ],
    'bumon_nm' => [
        'field' => 'bumon_nm',
        'title' => '受注部門名',
        'editable' => true,
        'width' => 135,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'bumon_cd',
            'bumon_nm',
            'kana'
        ],
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.bumon.create')
    ],
    'hatuti_cd' => [
        'field' => 'hatuti_cd',
        'title' => '発地CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hatuti_cd',
            'hatuti_nm',
            'kana'
        ], 
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'hatuti_nm'
        ],
        'link' => route('master.hachaku.create')
    ],
    'hatuti_nm' => [
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
        'link' => route('master.hachaku.create')
    ],
    'genkin_cd' => [
        'field' => 'genkin_cd',
        'title' => '現金CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'genkin_cd',
            'genkin_nm',
            'kana'
        ],
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'genkin_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    'genkin_nm' => [
        'field' => 'genkin_nm',
        'title' => '現金名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'genkin_cd',
            'genkin_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.meisyo.create')
    ],
    'ninusi_cd' => [
        'field' => 'ninusi_cd',
        'title' => '荷主CD',
        'editable' => true,
        'width' => 90,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'ninusi_cd'),
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'ninusi_nm'
        ],
        'link' => route('master.ninusi.create')
    ],
    'ninusi_nm' => [
        'field' => 'ninusi_nm',
        'title' => '荷主名',
        'editable' => true,
        'width' => 190,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'ninusi_cd',
            'ninusi_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.ninusi.create')
    ],
    'syuka_dt' => [
        'field' => 'syuka_dt',
        'title' => '集荷日',
        'editable' => true,
        'width' => 110,
        'type' => 'c_date',
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true
    ],
    'haitatu_dt' => [
        'field' => 'haitatu_dt',
        'title' => '配達日',
        'editable' => true,
        'width' => 110,
        'type' => 'c_date',
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true
    ],
    'unso_dt' => [
        'field' => 'unso_dt',
        'title' => '運送日',
        'editable' => true,
        'width' => 110,
        'type' => 'c_date',
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true
    ],
    'hachaku_cd' => [
        'field' => 'hachaku_cd',
        'title' => '着地CD',
        'editable' => true,
        'width' => 70,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'hachaku_cd'),
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'hachaku_nm'
        ],
        'link' => route('master.hachaku.create')
    ],
    'hachaku_nm' => [
        'field' => 'hachaku_nm',
        'title' => '着地名',
        'editable' => true,
        'width' => 230,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'hachaku_cd',
            'hachaku_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.hachaku.create')
    ],
    'su' => [
        'field' => 'su',
        'title' => '数量',
        'editable' => true,
        'width' => 105,
        'class' => 'size-L',
        'copitable' => true,
        'type' => 'numberic',
        'sortable' => true,
        'mask' => '#,##0.000',
    ],
    'hinmei_cd' => [
        'field' => 'hinmei_cd',
        'title' => '品名CD',
        'editable' => true,
        'width' => 80,
        'suggestion' => true,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'hinmei_cd'),
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'hinmoku_nm'
        ],
        'link' => route('master.hinmei.create')
    ],
    'hinmoku_nm' => [
        'field' => 'hinmoku_nm',
        'title' => '品目名',
        'editable' => true,
        'width' => 110,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'hinmoku_nm'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.hinmei.create')
    ],
    'hinmei_nm' => [
        'field' => 'hinmei_nm',
        'title' => '品名',
        'editable' => true,
        'width' => 230,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'hinmei_nm'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.hinmei.create')
    ],
    'tani_cd' => [
        'field' => 'tani_cd',
        'title' => '単位CD',
        'editable' => true,
        'width' => 65,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'tani_cd'),
        'class' => 'size-M',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'tani_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    'tani_nm' => [
        'field' => 'tani_nm',
        'title' => '単位名',
        'editable' => true,
        'width' => 65,
        'suggestion' => true,
        'suggestion_change' => [
            'tani_cd',
            'tani_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.meisyo.create')
    ],
    'jyotai' => [
        'field' => 'jyotai',
        'title' => '状態',
        'editable' => true,
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true
    ],
    'sitadori' => [
        'field' => 'sitadori',
        'title' => '下取',
        'editable' => true,
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true
    ],
    'gyosya_cd' => [
        'field' => 'gyosya_cd',
        'title' => '業者CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'gyosya_cd',
            'gyosya_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'gyosya_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    'gyosya_nm' => [
        'field' => 'gyosya_nm',
        'title' => '業者名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'gyosya_cd',
            'gyosya_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.meisyo.create')
    ],
    'tyuki' => [
        'field' => 'tyuki',
        'title' => '配達注記',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true
    ],
    'tanka_kbn' => [
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
    'tanka_nm' => [
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
        'link' => route('master.meisyo.create')
    ],
    'seikyu_tanka' => [
        'field' => 'seikyu_tanka',
        'title' => '基本運賃',
        'editable' => true,
        'width' => 95,
        'type' => 'numberic',
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true
    ],
    'unchin_kin' => [
        'field' => 'unchin_kin',
        'title' => '基本運賃',
        'width' => 95,
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberic'
    ],
    'tyukei_kin' => [
        'field' => 'tyukei_kin',
        'title' => '中継料',
        'editable' => true,
        'width' => 95,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberic'
    ],
    'tukoryo_kin' => [
        'field' => 'tukoryo_kin',
        'title' => '通行料等',
        'editable' => true,
        'width' => 95,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberic'
    ],
    'yosya_tukoryo_kin' => [
        'field' => 'yosya_tukoryo_kin',
        'title' => '庸車通行料等',
        'editable' => true,
        'width' => 120,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberic'
    ],
    'syuka_kin' => [
        'field' => 'syuka_kin',
        'title' => '集荷料',
        'editable' => true,
        'width' => 95,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberic'
    ],
    'tesuryo_kin' => [
        'field' => 'tesuryo_kin',
        'title' => '手数料',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
        'type' => 'numberic'
    ],
    'biko_cd' => [
        'field' => 'biko_cd',
        'title' => '備考CD',
        'editable' => true,
        'width' => 65,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'biko_cd'),
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'biko'
        ],
        'link' => route('master.biko.create')
    ],
    'biko' => [
        'field' => 'biko',
        'title' => '備考名',
        'editable' => true,
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.biko.create')
    ],
    'syaryo_kin' => [
        'field' => 'syaryo_kin',
        'title' => '車両金額',
        'type' => 'numberic',
        'editable' => true,
        'sortable' => true,
        'class' => 'size-L'
    ],
    'unten_kin' => [
        'field' => 'unten_kin',
        'title' => '運転者金額',
        'editable' => true,
        'width' => 95,
        'type' => 'numberic',
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true
    ],
    'unchin_mikakutei_kbn' => [
        'field' => 'unchin_mikakutei_kbn',
        'title' => '運賃確定区分',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'unchin_mikakutei_kbn',
            'unchin_mikakutei_nm',
            'kana'
        ],
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'unchin_mikakutei_nm'
        ],
        'link' => route('master.meisyo.create')
    ],
    'unchin_mikakutei_nm' => [
        'field' => 'unchin_mikakutei_nm',
        'title' => '運賃確定名',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'unchin_mikakutei_kbn',
            'unchin_mikakutei_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.meisyo.create')
    ],
    'yousya_cd' => [
        'field' => 'yousya_cd',
        'title' => '庸車先CD',
        'editable' => true,
        'width' => 80,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'yousya_cd'),
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'yousya_nm'
        ],
        'link' => route('master.yousya.create')
    ],
    'yousya_nm' => [
        'field' => 'yousya_nm',
        'title' => '庸車先名',
        'editable' => true,
        'width' => 210,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'yousya_cd',
            'yousya_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.yousya.create')
    ],
    'yosya_tyukei_kin' => [
        'field' => 'yosya_tyukei_kin',
        'title' => '庸車料',
        'editable' => true,
        'width' => 95,
        'type' => 'numberic',
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
    ],
    'yosya_tukoryo_kin' => [
        'field' => 'yosya_tukoryo_kin',
        'title' => '庸車通行料等',
        'editable' => true,
        'type' => 'numberic',
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
    ],
    'jisya_km' => [
        'field' => 'jisya_km',
        'title' => '実車km',
        'editable' => true,
        'width' => 85,
        'type' => 'numberic',
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
    ],
    'okurijyo_no' => [
        'field' => 'okurijyo_no',
        'title' => '送り状番号',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
    ],
    'jyutyu_kbn' => [
        'field' => 'jyutyu_kbn',
        'title' => '受注区分',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'jyutyu_kbn',
            'jyutyu_nm',
            'kana'
        ],
        'copyListHidden' => [
            'jyutyu_kbn',
            'jyutyu_nm'
        ],
        'sortable' => true,
    ],
    'jyutyu_nm' => [
        'field' => 'jyutyu_nm',
        'title' => '受注区分名',
        'editable' => true,
        'class' => 'size-L',
        'suggestion' => true,
        'suggestion_change' => [
            'jyutyu_kbn',
            'jyutyu_nm',
            'kana'
        ],
        'sortable' => true,
    ],
    'kaisyu_dt' => [
        'field' => 'kaisyu_dt',
        'title' => '回収日',
        'editable' => true,
        'class' => 'size-L',
        'type' => 'c_date',
        'copitable' => true,
        'sortable' => true,
    ],
    'kaisyu_kin' => [
        'field' => 'kaisyu_kin',
        'title' => '回収金額',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'sortable' => true,
    ],
    'jyomuin_cd' => [
        'field' => 'jyomuin_cd',
        'title' => '運転者CD',
        'editable' => true,
        'width' => 85,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'jyomuin_cd'),
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'jyomuin_nm'
        ],
        'link' => route('master.jyomuin.create'),
    ],
    'jyomuin_nm' => [
        'field' => 'jyomuin_nm',
        'title' => '運転者名',
        'editable' => true,
        'width' => 130,
        'align' => 'left',
        'suggestion' => true,
        'suggestion_change' => [
            'jyomuin_cd',
            'jyomuin_nm',
            'kana'
        ], 
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.jyomuin.create')
    ],
    'add_tanto_cd' => [
        'field' => 'add_tanto_cd',
        'title' => '入力担当CD',
        'editable' => true,
        'width' => 85,
        'type' => 'autocomplete',
        'remoteSearch' => true,
        'source' => [],
        'url' => route('order.order_entry.dispatch_suggestion', 'jyomuin_cd'),
        'class' => 'size-L',
        'sortable' => true,
        'copitable' => true,
        'copyListHidden' => [
            'jyomuin_nm'
        ],
        'link' => route('master.jyomuin.create'),
    ],
    'add_tanto_nm' => [
        'field' => 'add_tanto_nm',
        'title' => '入力担当名',
        'editable' => true,
        'width' => 130,
        'align' => 'left',
        'class' => 'size-L',
        'sortable' => true,
        'link' => route('master.jyomuin.create')
    ],
    'uriage_den_no' => [
        'field' => 'uriage_den_no',
        'title' => '売上番号',
        'editable' => false,
        'class' => 'size-L',
        'width' => 100,
        'sortable' => true,
        'copitable' => false,
        'align' => 'left',
        'visible' => true,
    ],
    'haitatu_tel' => [
        'field' => 'haitatu_tel',
        'title' => '配達TEL',
        'editable' => true,
        'copitable' => true, 
        'sortable' => true
    ],
    'haitatu_fax' => [
        'field' => 'haitatu_fax',
        'title' => '配達FAX',
        'editable' => true,
        'copitable' => true, 
        'sortable' => true
    ],
    'haitatu_atena' => [
        'field' => 'haitatu_atena',
        'title' => '配達宛名',
        'editable' => true,
        'copitable' => true,
        'sortable' => true
    ],
    'haitatu_jyusyo1' => [
        'field' => 'haitatu_jyusyo1',
        'title' => '配達住所1',
        'width' => 500,
        'align' => 'left',
        'editable' => true,
        'copitable' => true,
        'sortable' => true
    ],
    'haitatu_jyusyo2' => [
        'field' => 'haitatu_jyusyo2',
        'title' => '配達住所2',
        'width' => 500,
        'align' => 'left',
        'editable' => true,
        'copitable' => true,
        'sortable' => true
    ],
    'jikoku' => [
        'field' => 'jikoku',
        'title' => '配達時刻',
        'type' => 'time',
        'width' => 75,
        'editable' => true,
        'copitable' => true,
        'sortable' => true
    ],
];