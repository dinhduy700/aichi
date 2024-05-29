<?php 
    return [
        [   
            'title' => '配達日',
            'field' => 'haitatu_dt',
            'formatter' => 'formatDateGrid',
            'class' => 'text-center'
        ],
        [   
            'title' => '集荷日',
            'field' => 'syuka_dt',
            'formatter' => 'formatDateGrid',
            'class' => 'text-center'
        ],
        [   
            'title' => '着地',
            'field' => 'hachaku_nm',
        ],
        [   
            'title' => '品目名',
            'field' => 'hinmoku_nm',
        ],
        [   
            'title' => '品名',
            'field' => 'hinmei_nm',
        ],
        [   
            'title' => '数量',
            'field' => 'su',
            'formatter' => 'formatSu',
            'class' => 'text-right'
        ],
        [   
            'title' => '単位',
            'field' => 'tani_nm',
        ],
        [   
            'title' => 'URN',
            'field' => 'uriage_den_no',
            'formatter' => 'formatNumber',
            'class' => 'text-right'
        ]
    ];
?>