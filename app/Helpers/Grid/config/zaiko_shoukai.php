<?php

return [
    [
        'title' => '',
        'field' => 'index',
        'align' => 'center',
        'width' => '50'

    ],
    [
        'title' => '商品CD',
        'field' => 'hinmei_cd',
        'class' => 'size-M'
    ],
    [
        'title' => '商品名',
        'field' => 'hinmei_nm',
        'class' => 'size-L'
    ],
    [
        'title' => '商品規格',
        'field' => 'kikaku',
        'align' => 'center'
    ],
    [
        'title' => '入数',
        'field' => 'irisu',
        'width' => '75',
        'align' => 'right'
    ],
    [
        'title' => '在庫ケース数',
        'formatter' => 'formatZaikoCaseSu',
        'class' => 'size-M',
        'align' => 'right'
    ],
    [
        'title' => '単位',
        'field' => 'case_meisyo_nm',
        'width' => '75'
    ],

    [
        'title' => '在庫端数',
        'formatter' => 'formatZaikoHaSu',
        'halign' => 'center',
        'align' => 'right'
    ],
    [
        'title' => '在庫総数',
        'field' => 'zaiko_su',
        'halign' => 'center',
        'align' => 'right'
    ],
    [
        'title' => '単位',
        'field' => 'bara_tani_meisyo_nm',
        'width' => '75'
    ],
    [
        'title' => '在庫重量kg',
        'formatter' => 'formatZaikoJuryo',
        'halign' => 'center',
        'align' => 'right'
    ],
    [
        'title' => 'ロット１',
        'visible' => false,
        'field' => 'lot1',
        'width' => '75'
    ],
    [
        'title' => 'ロット２',
        'visible' => false,
        'field' => 'lot2',
        'width' => '75'
    ],
    [
        'title' => 'ロット3',
        'visible' => false,
        'field' => 'lot3',
        'width' => '75'
    ],
    [
        'field' => 'btn_list_ukebarai_shoukai',
        'editable' => false,
        'visible' => true,
        'suggestion' => false,
        'formatter' => 'displayBtnListUkebaraiShoukai',
        'align' => 'center'
    ],
];