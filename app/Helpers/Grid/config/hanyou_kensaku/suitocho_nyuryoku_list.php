<?php
return [
    [
        'title' => '受領日',
        'field' => 'kaisyu_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ],
    [
        'title' => '車番',
        'field' => 'syaban',
        'class' => 'text-center'
    ],
    [
        'title' => '運転者',
        'field' => 'jyomuin_nm',
    ],
    [
        'title' => '科目',
        'field' => 'field_no4'
    ],
    [
        'title' => '部門コード',
        'field' => 'bumon_cd'
    ],
    [
        'title' => '部門名',
        'field' => 'bumon_nm',
    ],
    [
        'title' => '荷主コード',
        'field' => 'ninusi_cd'
    ],
    [
        'title' => 'ホール',
        'field' => 'ninusi1_nm'
    ],
    [
        'title' => '業者コード',
        'field' => 'gyosya_cd'
    ],
    [
        'title' => '業者',
        'field' => 'gyosya_nm'
    ],
    [
        'title' => '税込回収金額',
        'field' => 'total_1',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '回収金額',
        'field' => 'total_2',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '配達日',
        'field' => 'haitatu_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ]
];
?>