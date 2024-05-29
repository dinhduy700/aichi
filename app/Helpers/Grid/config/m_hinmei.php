<?php 
return [
    [
        'field' => 'hinmei_cd',
        'title' => '品名コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'formatter' => 'formatHinmeiCd',
        'class' => 'text-right',
        'sortable' => true,
    ],
    [
        'field' => 'kana',
        'title' => 'ヨミガナ',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M',
        'sortable' => true,
    ],
    [
        'field' => 'hinmei_nm',
        'title' => '名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M',
        'sortable' => true,
    ],
    [
        'field' => 'hinmei2_cd',
        'title' => '品名2コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'hinmoku_cd',
        'title' => '品目コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'hinmoku_nm',
        'title' => '品目名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'tani_cd',
        'title' => '単位コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'tani_nm',
        'title' => '単位名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'tani_jyuryo',
        'title' => '単位重量',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'haisya_tani_jyuryo',
        'title' => '配車単位重量',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'syoguti_kbn1',
        'title' => '諸口区分１',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M',
        'formatter' => 'formatSyogutiKbn1',
        'options' => configParam('options.m_hinmei.syoguti_kbn1', [], 1)
    ],
    [
        'field' => 'syoguti_kbn2',
        'title' => '諸口区分２',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M',
        'formatter' => 'formatSyogutiKbn2',
        'options' => configParam('options.m_hinmei.syoguti_kbn2', [], 1)
    ],
    [
        'field' => 'ninusi_id',
        'title' => '荷主コード',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
    [
        'field' => 'ninusi_nm',
        'title' => '荷主名称',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M'
    ],
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
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'editable' => false,
        'visible' => true,
        'width' => '200',
        'suggestion' => false,
        'class' => 'size-M',
        'formatter' => 'formatKyuminFlg',
        'options' => configParam('options.m_hinmei.kyumin_flg', [], 1)
    ],
];