<?php 
return [
    [
        'title' => '配達日',
        'field' => 'haitatu_dt',
        'class' => 'text-center',
        'formatter' => 'formatDateGrid'
    ],
    [
        'title' => '着地名',
        'field' => 'hachaku_nm'
    ],
    [
        'title' => '品目名',
        'field' => 'hinmoku_nm'
    ],
    [
        'title' => '品名',
        'field' => 'hinmei_nm'
    ],
    [
        'title' => '種別',
        'field' => 'syubetu_nm'
    ],
    [
        'title' => '数量',
        'field' => 'su',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '基本運賃',
        'field' => 'field_no7',
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
        'title' => '消費税',
        'field' => 'seikyu_kin_tax',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '税込み金額',
        'field' => 'field_no10',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ]
];
?>