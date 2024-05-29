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
        'A' => ['field' => 'bumon_cd'],
        'B' => ['field' => 'bumon_nm'],
        'C' => ['field' => 'soko_cd'],
        'D' => ['field' => 'kana'],
        'E' => ['field' => 'soko_nm'],
        'F' => ['field' => 'kyumin_flg', 'value' => function($row) {
            return data_get(
                configParam('options.m_soko.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],

];