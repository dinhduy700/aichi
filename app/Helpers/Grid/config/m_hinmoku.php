<?php 
return [
    [
        'field' => 'hinmoku_cd',
        'title' => '品目コード',
        'editable' => false,
        'visible' => true,
        'width' => 200,
        'suggestion' => false,
        'class' => 'size-M',
        'formatter' => 'formatHinmokuCd'
    ],
    [
        'field' => 'kana',
        'title' => 'ヨミガナ',
        'editable' => false,
        'visible' => true,
        'width' => 200,
        'suggestion' => false,
        'class' => 'size-L',
    ],
    [
        'field' => 'hinmoku_nm',
        'title' => '名称',
        'editable' => false,
        'visible' => true,
        'width' => 200,
        'suggestion' => false,
        'class' => 'size-2L',
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
        'options' => configParam('options.m_hinmoku.kyumin_flg', [], 1)
    ],
];