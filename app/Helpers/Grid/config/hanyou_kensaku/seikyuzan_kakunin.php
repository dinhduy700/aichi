<?php 
return [
    [
        'title' => '荷主コード',
        'field' => 'ninusi_cd'
    ],
    [
        'title' => '荷主名',
        'field' => 'ninusi1_nm'
    ],
    [
        'title' => '請求締日',
        'field' => 'seikyu_sime_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ],
    [
        'title' => '請求金額',
        'field' => 'konkai_torihiki_kin',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '入金金額',
        'field' => 'total_no5',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'title' => '未消込金額',
        'field' => 'total_no6',
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ]
];
?>