<?php 
return [
    [
        'field' => 'bumon_cd',
        'title' => '部門コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'bumon_nm',
        'title' => '部門名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'soko_cd',
        'title' => '倉庫コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'formatter' => 'formatSokoCd',
        'class' => 'size-M'
    ],
    [
        'field' => 'soko_nm',
        'title' => '倉庫名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'kana',
        'title' => 'ヨミガナ',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M',
        'formatter' => 'formatKyuminFlg',
        'options' => configParam('options.m_soko.kyumin_flg', [], 1)
    ],
];