<?php 
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'H', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'bumon_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'bumon_nm'],
        'D' => ['field' => 'kyumin_flg', 'value' => function($row) {
            return data_get(
                configParam('options.m_bumon.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],

];