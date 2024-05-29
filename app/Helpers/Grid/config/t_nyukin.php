<?php 
return [
    [
        'field' => 'nyukin_no',
        'title' => '入金NO',
        'editable' => false,
        'formatter' => 'formatNyukinNo'
    ],
    [
        'field' => 'nyukin_dt',
        'title' => '入金日',
        'editable' => false,
        'align' => 'center',
        'formatter' => 'formatNyukinDt'
    ],
    [
        'field' => 'ninusi_cd',
        'title' => '荷主CD',
        'editable' => false
    ],
    [
        'field' => 'ninusi_nm',
        'title' => '荷主名',
        'editable' => false
    ],
    [
        'field' => 'nyukin_sum',
        'title' => '入金額合計',
        'editable' => false,
        'formatter' => 'formatNyukinSum',
        'align' => 'right'
    ],
    [
        'field' => 'biko',
        'title' => '備考',
        'editable' => false
    ]
];