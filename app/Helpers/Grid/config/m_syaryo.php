<?php
return [
    [
        'field' => 'syaryo_cd',
        'title' => '車両コード',
        'formatter' => 'formatSyaryoCd'
    ],
    [
        'field' => 'syasyu_cd',
        'title' => '車種コード',
        'formatter' => 'formatSyaryo'
    ],
    [
        'field' => 'syasyu_nm',
        'title' => '車種名称',
        'formatter' => 'formatSyaryo'
    ],
    [
        'field' => 'jiyo_kbn',
        'title' => '自庸区分',
        'formatter' => 'formatSyaryoKbn',
        'options' => configParam('options.m_syaryo.jiyo_kbn', [], 1)
    ],
    [
        'field' => 'jyomuin_cd',
        'title' => '乗務員コード',
        'formatter' => 'formatSyaryo'
    ],
    [
        'field' => 'jyomuin_nm',
        'title' => '乗務員名称',
        'formatter' => 'formatSyaryo'
    ],
    [
        'field' => 'yousya_cd',
        'title' => '庸車コード',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'yousya_nm',
        'title' => '庸車名称',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'bumon_cd',
        'title' => '部門コード',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'bumon_nm',
        'title' => '部門名称',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'sekisai_kbn',
        'title' => '積載区分',
        'formatter' => 'formatSyaryoKbn',
        'options' => configParam('options.m_syaryo.sekisai_kbn', [], 1)
    ],
    [
        'field' => $f = 'sekisai_jyuryo',
        'title' => trans("attributes.m_syaryo.{$f}"),
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'point',
        'title' => 'ポイント',
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'himoku_ritu',
        'title' => '費目計算用率',
        'class' => 'text-right',
        'formatter' => 'formatNumber',
    ],
    [
        'field' => 'haisya_dt',
        'title' => '廃車日付',
        'class' => 'text-center',
        'formatter' => 'formatDate',
    ],
    [
        'field' => 'rikuun_cd',
        'title' => '陸運支局コード',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'rikuun_nm',
        'title' => '陸運支局名称',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'car_number_syubetu',
        'title' => '種別',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'car_number_kana',
        'title' => 'かな',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'car_number',
        'title' => 'ナンバー',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'haisya_biko',
        'title' => '配車備考',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'biko',
        'title' => '備考',
        'formatter' => 'formatSyaryo',
    ],
    [
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'formatter' => 'formatSyaryoKbn',
        'options' => configParam('options.m_syaryo.kyumin_flg', [], 1)
    ],
];
