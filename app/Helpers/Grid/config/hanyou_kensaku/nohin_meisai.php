<?php
return [
    [
        'title' => '配達日',
        'field' => 'haitatu_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
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
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '基本運賃',
        'field' => 'kihon_unchin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '中継料',
        'field' => 'tyukei_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '消費税',
        'field' => 'seikyu_kin_tax',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '税込み金額',
        'field' => 'zeikomi_kingaku',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
];
