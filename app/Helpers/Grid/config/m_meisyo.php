<?php
return [
    [
        'field' => 'meisyo_kbn',
        'title' => '名称区分',
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => 'formatMeisyoKbn'
    ],
    [
        'field' => 'meisyo_cd',
        'title' => '名称コード',
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => "formatMeisyoCd",
        'sortable' => true,
    ],
    [
        'field' => 'kana',
        'title' => 'ヨミガナ',
        'editable' => false,
        'visible' => true,
        'suggestion' => false
    ],
    [
        'field' => 'meisyo_nm',
        'title' => '名称',
        'editable' => false,
        'visible' => true,
        'suggestion' => false
    ],
    [
        'field' => 'jyuryo_kansan',
        'title' => '重量換算係',
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'class' => 'text-right',
        'formatter' => 'formatNumber'
    ],
    [
        'field' => 'sekisai_kbn',
        'title' => '積載区分',
        'editable' => false,
        'visible' => true,
        'suggestion' => false
    ],
    [
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'class' => 'text-center',
        'formatter' => 'formatKyuminFlg',
        'options' => configParam('options.m_meisyo.kyumin_flg', [], 1)
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
