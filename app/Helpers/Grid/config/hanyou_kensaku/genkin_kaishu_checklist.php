<?php 
return [
    [
        'title' => '回収日',
        'field' => 'field_no1'
    ],
    [
        'title' => '科目',
        'field' => 'field_no2'
    ],
    [
        'title' => '業者',
        'field' => 'gyosya_nm'
    ],
    [
        'title' => 'ホール',
        'field' => 'hachaku_nm'
    ],
    [
        'title' => '回収金額',
        'field' => 'field_no6',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '運転者',
        'field' => 'jyomuin_nm'
    ],
    [
        'title' => '配達日',
        'field' => 'haitatu_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ]
];
    
?>