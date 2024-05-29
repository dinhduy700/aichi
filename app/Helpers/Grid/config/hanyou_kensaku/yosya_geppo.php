<?php
return [
    [
        'title' => '日報日付',
        'field' => 'nipou_dt',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ],
    [
        'title' => '傭車',
        'field' => 'yousya_ryaku_nm'
    ],
    [
        'title' => '荷主',
        'field' => 'ninusi_ryaku_nm'
    ],
    [
        'title' => '種別',
        'field' => 'syubetu_nm'
    ],
    [
        'title' => '業者',
        'field' => 'gyosya_nm'
    ],
    [
        'title' => '発地',
        'field' => 'hatuti_nm'
    ],
    [
        'title' => '着地',
        'field' => 'hachaku_nm'
    ],
    [
        'title' => 'ﾒｰｶｰ',
        'field' => 'hinmoku_nm'
    ],
    [
        'title' => '品名',
        'field' => 'hinmei_nm'
    ],
    [
        'title' => '数量',
        'field' => 'su',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '単位',
        'field' => 'tani_nm'
    ],
    [
        'title' => '基本運賃',
        'field' => 'unchin_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '通行料',
        'field' => 'tukoryo_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' =>'手数料',
        'field' => 'tesuryo_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '中継料',
        'field' => 'tyukei_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '集荷料',
        'field' => 'syuka_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '傭車料',
        'field' => 'yosya_tyukei_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '傭車通行料',
        'field' => 'yosya_tukoryo_kin',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'title' => '支払運賃合計',
        'field' => 'total',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ]
];
?>