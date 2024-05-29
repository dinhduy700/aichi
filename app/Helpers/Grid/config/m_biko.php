<?php 
return [
 
    [
        'field' => 'biko_cd',
        'title' => trans('attributes.m_biko.biko_cd'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => "formatBikoCd",
    ],
    [
        'field' => 'kana',
        'title' => trans('attributes.m_biko.kana'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'biko_nm',
        'title' => trans('attributes.m_biko.biko_nm'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'syubetu_kbn',
        'title' => trans('attributes.m_biko.syubetu_kbn'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => 'formatSyubetuKbn',
        'options' => configParam('options.m_biko.syubetu_kbn', [], 1)
    ],
    [
        'field' => 'kyumin_flg',
        'title' => trans('attributes.m_biko.kyumin_flg'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => 'formatKyuminFlg',
        'options' => configParam('options.m_biko.kyumin_flg', [], 1)
    ],
    [
        'field' => 'add_user_cd',
        'title' => '登録者',
        'editable' => false,
        'visible' => false,
        'suggestion' => false
    ],
    [
        'field' => 'add_dt',
        'title' => '登録日',
        'editable' => false,
        'visible' => false,
        'suggestion' => false
    ],
    [
        'field' => 'upd_user_cd',
        'title' => '更新者',
        'editable' => false,
        'visible' => false,
        'width' => 200,
        'suggestion' => false
    ],
    [
        'field' => 'upd_dt',
        'title' => '更新日',
        'editable' => false,
        'visible' => false,
        'width' => 200,
        'suggestion' => false
    ],

];