<?php
    return [
        [
            'field' => 'soko_nm',
            'title' => '倉庫',
            'formatter' => 'formatterSokoNm'
        ],
        [
            'field' => 'location',
            'title' => 'ロケーション'
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
            'field' => 'hikiate_kano_su',
            'title' => '引当可能数',
            'formatter' => 'formatterHikiateKannoSu',
            'align' => 'right',
            'width' => 200
        ],
        [
            'field' => 'hikiate_su',
            'title' => '引当数',
            'formatter' => 'formatterHikiateSu',
            'width' => 200,
            'align' => 'right',
        ],
        [
            'field' => 'zensu',
            'title' => '全数引当',
            'formatter' => 'formatterZensu',
            'width' => 100
        ]
    ];
?>