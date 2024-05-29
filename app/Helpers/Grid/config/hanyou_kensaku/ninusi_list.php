<?php 
return [
    [
        'title' => '運送日付',
        'field' => 'unso_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ],
    [
        'title' => '車番',
        'field' => 'syaban'
    ],
    [
        'title' => '運転者',
        'field' => 'jyomuin_nm'
    ],
    [
        'title' => '荷主',
        'field' => 'ninusi_ryaku_nm'
    ],
    [
        'title' => '種別',
        'field' => 'syubetu_nm'
    ],
    [
        'title' => '業者',
        'field' => 'gyosya_nm'
    ],
    [
        'title' => '発地',
        'field' => 'hatuti_nm'
    ],
    [
        'title' => '着地',
        'field' => 'hachaku_nm'
    ],
    [
        'title' => 'ﾒｰｶｰ',
        'field' => 'hinmoku_nm'
    ],
    [
        'title' => '品名',
        'field' => 'hinmei_nm'
    ],
    [
        'title' => '数量',
        'field' => 'su'
    ],
    [
        'title' => '単位',
        'field' => 'tani_nm'
    ],
    [
        'title' => '基本運賃',
        'field' => 'unchin_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '通行料',
        'field' => 'tukoryo_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '手数料',
        'field' => 'tesuryo_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '中継料',
        'field' => 'tyukei_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '集荷料',
        'field' => 'syuka_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '運転者金額',
        'field' => 'unten_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '集金金額',
        'field' => 'kaisyu_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '傭車',
        'field' => 'yousya_ryaku_nm'
    ],
    [
        'title' => '傭車料',
        'field' => 'yosya_tyukei_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '傭車通行料',
        'field' => 'yosya_tukoryo_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '支払運賃合計',
        'field' => 'total_yosya',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ]
];

?>