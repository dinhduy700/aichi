<?php
return [
    [
        'field' => 'checkbox',
        'checkbox' => true,
        'width' => 50,
    ],
    [
        'field' => 'ninusi_cd',
        'title' => '荷主コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
    ],
    [
        'field' => 'ninusi_ryaku_nm',
        'title' => '荷主名',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
    ],
    [
        'field' => 'zenkai_seikyu_kin',
        'title' => '前回請求額',
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'nyukin_kin',
        'title' => '入金額',
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'seikyu_kin',
        'title' => '取引額',
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'konkai_torihiki_kin',
        'title' => '請求額',
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'seikyu_hako_flg',
        'title' => '発行状況',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
    ],
    [
        'field' => 'seikyu_kakutei_flg',
        'title' => '確定状況',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
    ],
];