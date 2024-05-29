<?php
return [
    [
        'title' => '日付',
        'field' => 'kisan_dt'
    ],
    [
        'title' => '区分',
        'formatter' => 'formatNyusyukoKbn'
    ],
    [
        'title' => '内訳',
        'field' => 'uchiwake'
    ],
    [
        'title' => '入庫ケース',
        'field' => 'in_case_su',
        'halign' => 'center',
        'align' => 'right',
    ],
    [
        'title' => '入庫端数',
        'field' => 'in_hasu',
        'halign' => 'center',
        'align' => 'right',
    ],
    [
        'title' => '入庫総数',
        'field' => 'in_su',
        'halign' => 'center',
        'align' => 'right',
        'formatter' => 'formatNumber',
    ],
    [
        'title' => '出庫ケース',
        'field' => 'out_case_su',
        'halign' => 'center',
        'align' => 'right',
    ],
    [
        'title' => '出庫端数',
        'field' => 'out_hasu',
        'halign' => 'center',
        'align' => 'right',
    ],
    [
        'title' => '出庫総数',
        'field' => 'out_su',
        'halign' => 'center',
        'align' => 'right',
        'formatter' => 'formatNumber',
    ],
    [
        'title' => '伝票番号',
        'field' => 'nyusyuko_den_no',
        'halign' => 'center',
        'align' => 'right',
    ],
    [
        'title' => '入出庫先',
        'field' => 'todokesaki_nm',
        'halign' => 'center',
    ],
    [
        'title' => 'ロット１',
        'visible' => false,
        'width' => '75',
        'field' => 'lot1',
    ],
    [
        'title' => 'ロット２',
        'visible' => false,
        'width' => '75',
        'field' => 'lot2',
    ],
    [
        'title' => 'ロット３',
        'visible' => false,
        'width' => '75',
        'field' => 'lot3',
    ],
    [
        'title' => '倉庫・ロケーション',
        'field' => 'soko_cd_location'
    ],
    [
        'title' => '扱便',
        'field' => 'biko'
    ]
];