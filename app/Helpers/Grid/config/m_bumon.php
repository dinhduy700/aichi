<?php 
return [
    [
        'field' => 'bumon_cd',
        'title' => '部門コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'formatter' => 'formatBumonCd',
        'class' => 'size-M'
    ],
    [
        'field' => 'kana',
        'title' => 'ヨミガナ',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-L'
    ],
    [
        'field' => 'bumon_nm',
        'title' => '名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-2L'
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
        'options' => configParam('options.m_bumon.kyumin_flg', [], 1)
    ],

];