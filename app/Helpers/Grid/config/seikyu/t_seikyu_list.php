<?php
$colAttr = ['editable' => false, 'visible' => true, 'suggestion' => false,];
return [
    ['checkbox' => true],
    array_merge($colAttr, ['field' => 'ninusi_cd', 'title' => 'CD']),
    array_merge($colAttr, ['field' => 'ninusi_ryaku_nm', 'title' => '荷主']),
    array_merge($colAttr, ['field' => 'zenkai_seikyu_kin', 'title' => '前回請求額', 'formatter' => 'formatNumber', 'class' => 'text-right']),
    array_merge($colAttr, ['field' => 'nyukin_kin', 'title' => '入金額', 'formatter' => 'formatNumber', 'class' => 'text-right']),
    array_merge($colAttr, ['field' => 'konkai_torihiki_kin', 'title' => '取引額', 'formatter' => 'formatNumber', 'class' => 'text-right']),
    array_merge($colAttr, ['field' => 'konkai_torihiki_kin', 'title' => '請求額', 'formatter' => 'formatNumber', 'class' => 'text-right']),
    array_merge($colAttr, ['field' => 'mikakutei_su', 'title' => '未確定数', 'formatter' => 'formatNumber', 'class' => 'text-right']),
    array_merge($colAttr, ['field' => 'mikakutei_kin', 'title' => '未確定額', 'formatter' => 'formatNumber', 'class' => 'text-right']),
    array_merge($colAttr, [
        'field' => 'seikyu_hako_flg',
        'title' => '発行状況',
        'formatter' => 'formatHakoFlg',
        'options' => configParam('options.t_seikyu.seikyu_hako_flg'),
        'class' => 'text-center',
    ]),
    array_merge($colAttr, [
        'field' => 'seikyu_kakutei_flg',
        'title' => '確定状況',
        'formatter' => 'formatKakuteiFlg',
        'options' => configParam('options.t_seikyu.seikyu_kakutei_flg'),
        'class' => 'text-center',
    ]),
];
