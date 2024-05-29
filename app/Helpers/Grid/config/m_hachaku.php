<?php 
return [
 
    [
        'field' => 'hachaku_cd',
        'title' => trans('attributes.m_hachaku.hachaku_cd'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => "formatHachakuCd",
    ],
    [
        'field' => 'kana',
        'title' => trans('attributes.m_hachaku.kana'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'hachaku_nm',
        'title' => trans('attributes.m_hachaku.hachaku_nm'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'atena_ninusi_id',
        'title' => trans('attributes.m_hachaku.atena_ninusi_id'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'atena_ninusi_nm',
        'title' => trans('attributes.m_hachaku.atena_ninusi_nm'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'atena',
        'title' => trans('attributes.m_hachaku.atena'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'jyusyo1_nm',
        'title' => trans('attributes.m_hachaku.jyusyo1_nm'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'jyusyo2_nm',
        'title' => trans('attributes.m_hachaku.jyusyo2_nm'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'tel',
        'title' => trans('attributes.m_hachaku.tel'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'fax',
        'title' => trans('attributes.m_hachaku.fax'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'ninusi_id',
        'title' => trans('attributes.m_hachaku.ninusi_id'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'ninusi_nm',
        'title' => trans('attributes.m_hachaku.ninusi_nm'),
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
    ],
    [
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => 'formatKyuminFlg',
        'options' => configParam('options.m_hachaku.kyumin_flg', [], 1)
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
        'suggestion' => false
    ],
    [
        'field' => 'upd_dt',
        'title' => '更新日',
        'editable' => false,
        'visible' => false,
        'suggestion' => false
    ],

];