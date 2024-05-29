<?php 

return [
    [
        'field' => 'choice',
        'title' => '',
        'editable' => true,
        'formatter' => 'inputsEntryHead',
        'width' => 60
    ],
    [
        'field' => 'nyusyuko_den_no',
        'title' => '伝票NO',
    ],
    [
        'field' => 'denpyo_dt',
        'title' => '伝票日付',
        'formatter' => 'formatDateGrid'
    ],
    [
        'field' => 'ninusi_ryaku_nm',
        'title' => '荷主名',
    ],
    [
        'field' => 'hinmei_nm',
        'title' => '１行目の商品名'
    ],
    [

        'field' => 'kikaku',
        'title' => '規格'
    ],
    [
        'field' => 'lot1',
        'title' => 'ロット１'
    ],
    [
        'field' => 'lot2',
        'title' => 'ロット２'
    ],
    [
        'field' => 'lot3',
        'title' => 'ロット３'
    ],
    [
        'field' => 'su',
        'title' => '総数  ',
        'formatter' => 'formatNumber',
        'type' => 'number',
        'class' => 'text-right'
    ],
    [
        'field' => 'tani_nm',
        'title' => '単位  '
    ],
    [
        'field' => 'biko',
        'title' => '伝票備考'
    ]
];

?>