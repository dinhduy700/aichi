<?php
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'H', 'row' => 4],
        ],
        'others' => [
            ['col' => 'E', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'jyomuin_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'jyomuin_nm'],
        'D' => ['field' => 'bumon_cd'],
        'E' => ['field' => 'bumon_nm'],
        'F' => ['field' => 'mobile_tel'],
        'G' => ['field' => 'mail'],
        'H' => ['field' => 'kyumin_flg', 'value' => function($row) {
            return data_get(
                configParam('options.m_jyomuin.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],

];
