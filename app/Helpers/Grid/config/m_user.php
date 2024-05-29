<?php
return [
    [
        'field' => 'user_cd',
        'title' => 'ユーザID',
        'formatter' => 'formatUserCd',
    ],
    [
        'field' => 'group',
        'title' => '権限グループ',
        'formatter' => 'formatUserKbn',
        'options' => configParam('options.m_user.group', [], 1)
    ],
    [
        'field' => 'biko',
        'title' => '備考',
    ],
    [
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'formatter' => 'formatUserKbn',
        'options' => configParam('options.m_user.kyumin_flg', [], 1)
    ],
];
