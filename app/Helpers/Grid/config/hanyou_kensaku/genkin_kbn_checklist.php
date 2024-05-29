<?php 
return [
    [
        'field' => 'syaban',
        'title' => '車番'
    ],
    [
        'field' => 'jyomuin_cd',
        'title' => '乗務員CD'
    ],
    [
        'field' => 'jyomuin_nm',
        'title' => '乗務員名'
    ],
    [
        'field' => 'haitatu_dt',
        'title' => '配達日',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ],
    [
        'field' => 'jikoku',
        'title' => '配達時刻'
    ],
    [
        'field' => 'ninusi_ryaku_nm',
        'title' => '荷主'
    ],
    [
        'field' => 'hachaku_nm',
        'title' => '着地'
    ],
    [
        'field' => 'hinmoku_nm',
        'title' => 'ﾒｰｶｰ'
    ],
    [
        'field' => 'syubetu_nm',
        'title' => '種別'
    ],
    [
        'field' => 'su',
        'title' => '数量',
        'formatter' => 'formatSu',
    ],
    [
        'field' => 'hinmei_nm',
        'title' => '機種'
    ],
    [
        'field' => 'syuka_dt',
        'title' => '集荷日',
        'formatter' => 'formatDateGrid',
        'class' => 'text-center'
    ],
    [
        'field' => 'sitadori',
        'title' => '下取'
    ],
    [
        'field' => 'jyotai',
        'title' => '状態'
    ],
    [
        'field' => 'gyosya_nm',
        'title' => '業者'
    ],
    [
        'field' => 'unchin_kin',
        'title' => '基本運賃',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'field' => 'tyukei_kin',
        'title' => '中継料',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'field' => 'syuka_kin',
        'title' => '集荷料',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'field' => 'tesuryo_kin',
        'title' => '手数料',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'field' => 'unten_kin',
        'title' => '運転者金額',
        'formatter' => 'formatNumber',
        'class' => 'text-right'
    ],
    [
        'field' => 'biko',
        'title' => '備考'
    ],
    [
        'field' => 'uriage_den_no',
        'title' => '売上番号'
    ]
];
?>