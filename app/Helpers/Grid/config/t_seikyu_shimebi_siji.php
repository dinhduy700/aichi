<?php 
return [
    [
        'field' => 'btn_update',
        'title' => '締更新',
        'formatter' => 'displayBtnInsert',
        'class' => 'size-S'
    ],
    [
        'field' => 'btn_delete',
        'title' => '削除',
        'formatter' => 'displayBtnDelete',
        'class' => 'size-S'
    ],
    [
        'field' => 'seikyu_sime_dt',
        'title' => '締日',
        'class' => 'text-center',
        'formatter' => 'formatDate',
    ],
    [
        'field' => 'seikyu_hako_flg',
        'title' => '請求書発行状況',
    ],
    [
        'field' => 'seikyu_kakutei_flg',
        'title' => '請求書確定状況',
    ],
    
];