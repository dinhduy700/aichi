<?php 
return [
    [
        'field' => 'checkbox',
        'checkbox' => true,
        'width' => 50
    ],
    [
        // 'field' => 'open-modal',
        'width' => 100,
        'formatter' => 'openModal'
    ],
    [
        'field' => 'nyusyuko_den_meisai_no',
        'title' => 'SEQ',
        'editable' => false,
        'copytable' => false,
        'formatterFooter' => true
    ],
    [
        'field' => 'hinmei_cd',
        'title' => '商品CD',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hinmei_cd',
            'hinmei_nm',
            'kana',
            'kikaku',
            'irisu',
            'tani_cd',
            'tani_nm'
        ],
        'suggestion_hide' => [
            'kikaku',
            'irisu',
            'tani_cd',
            'tani_nm'
        ],
        'class' => 'size-L',
        'sortable' => false,
        'copitable' => true,
        'url_suggestion' => route('nyusyuko.nyuryoku.suggestion_multiple'),
        'otherFieldElements' => [
            'input[name="ninusi_cd"]',
            // 'input[name="hed_bumon_cd"]'
        ]
    ],
    [
        'field' => 'hinmei_nm',
        'title' => '商品',
        'editable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'hinmei_cd',
            'hinmei_nm',
            'kana',
            'kikaku',
            'irisu',
            'tani_cd',
            'tani_nm'
        ],
        'suggestion_hide' => [
            'kikaku',
            'irisu',
            'tani_cd',
            'tani_nm'
        ],
        'class' => 'size-L',
        'sortable' => false,
        'copitable' => true,
        'url_suggestion' => route('nyusyuko.nyuryoku.suggestion_multiple'),
        'otherFieldElements' => [
            'input[name="ninusi_cd"]',
            // 'input[name="hed_bumon_cd"]'
        ]
    ],
    [
        'field' => 'kikaku',
        'title' => '商品規格',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
        'formatter' => 'formatKikaku',
        'formatterFooter' => true
    ],
    [
        'field' => 'lot1',
        'title' => 'ロット１',
        'editable' => true,
        'class' => 'size-L',
        'copitable' => true,
    ],
    [
        'field' => 'lot2',
        'title' => 'ロット２',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
    ],
    [
        'field' => 'lot3',
        'title' => 'ロット３',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
    ],
    [
        'field' => 'irisu',
        'title' => '入り数',
        'class' => 'size-L',
        'type' => 'number',
        'editable' => true,
        'copitable' => true,
        'formatter' => 'formatterIrisu',
        'formatterFooter' => true
    ],
    [
        'field' => 'case_su',
        'title' => 'ケース数',
        'class' => 'size-L',
        'type' => 'number',
        'editable' => true,
        'copitable' => true,
        'formatter' => 'formatterCaseSu',
        'formatterFooter' => true
    ],
    [
        'field' => 'hasu',
        'title' => '端数',
        'class' => 'size-L',
        'type' => 'number',
        'editable' => true,
        'copitable' => true,
        'formatter' => 'formatterHasu',
        'formatterFooter' => true
    ],
    [
        'field' => 'su',
        'title' => '総数',
        'class' => 'size-L',
        'type' => 'number',
        'editable' => true,
        'copitable' => true,
        'formatter' => 'formatterSu',
        'formatterFooter' => true
    ],
    [
        'field' => 'tani_cd',
        'title' => '単位CD',
        'editable' => true,
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'tani_cd',
            'kana',
            'tani_nm'
        ]
    ],
    [
        'field' => 'tani_nm',
        'title' => '単位名',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'tani_cd',
            'kana',
            'tani_nm'
        ]
    ],
    [
        'field' => 'jyuryo',
        'title' => '重量／㎥',
        'class' => 'size-L',
        'editable' => true,
        'type' => 'number',
        'formatter' => 'formatterjJyuryo',
        'formatterFooter' => true
    ],
    [
        'field' => 'soko_cd',
        'title' => '倉庫CD',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'soko_cd',
            'kana',
            'soko_nm'
        ],
        'url_suggestion' => route('nyusyuko.nyuryoku.suggestion_multiple'),
        'otherFieldElements' => [
            'input[name="hed_bumon_cd"]'
        ]
    ],
    [
        'field' => 'soko_nm',
        'title' => '倉庫名',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'soko_cd',
            'kana',
            'soko_nm'
        ],
        'url_suggestion' => route('nyusyuko.nyuryoku.suggestion_multiple'),
        'otherFieldElements' => [
            'input[name="hed_bumon_cd"]'
        ]
    ],
    [
        'field' => 'location',
        'title' => 'ロケーション',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
    ],
    [
        'field' => 'soko_cd_to',
        'title' => '移動先倉庫',
        'editable' => true,
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'soko_cd_to',
            'kana',
            'soko_nm_to'
        ],
        'url_suggestion' => route('nyusyuko.nyuryoku.suggestion_multiple'),
        'otherFieldElements' => [
            'input[name="hed_bumon_cd"]'
        ]
    ],
    [
        'field' => 'soko_nm_to',
        'title' => '移動先の倉庫名',
        'editable' => true,
        'copitable' => true,
        'suggestion' => true,
        'suggestion_change' => [
            'soko_cd_to',
            'kana',
            'soko_nm_to'
        ],
        'url_suggestion' => route('nyusyuko.nyuryoku.suggestion_multiple'),
        'otherFieldElements' => [
            'input[name="hed_bumon_cd"]'
        ]
    ],
    [
        'field' => 'location_to',
        'title' => '移動先ロケ',
        'editable' => true,
        'copitable' => true,
        'class' => 'size-L',
    ],
    [
        'field' => 'biko',
        'title' => '備考',
        'class' => 'size-L',
        'editable' => true,
        'copitable' => true,
    ]
];
?>