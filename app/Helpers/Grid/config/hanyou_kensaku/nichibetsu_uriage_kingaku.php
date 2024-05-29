<?php 

return [
    [
        'title' => 'コード',
        'field' => 'ninusi_cd'
    ],
    [
        'title' => '荷主',
        'field' => 'ninusi1_nm'
    ],
    [
        'title' => '配達日',
        'field' => 'haitatu_dt',
        'formatter' => 'formatDateGrid'
    ],
    [
        'title' => '基本運賃',
        'field' => 'unchin_kin',
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
        'title' => '集荷料',
        'field' => 'syuka_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '手数料',
        'field' => 'tesuryo_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '運転者金額',
        'field' => 'unten_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ]
];