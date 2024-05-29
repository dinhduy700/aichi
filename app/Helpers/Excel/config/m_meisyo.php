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
        'A' => ['field' => 'meisyo_kbn'],
        'B' => ['field' => 'meisyo_cd'],
        'C' => ['field' => 'kana'],
        'D' => ['field' => 'meisyo_nm'],
        'E' => ['field' => 'jyuryo_kansan'],
        'F' => ['field' => 'sekisai_kbn'],
        'G' => ['field' => 'kyumin_flg', 'value' => function($row) {
            return data_get(
                configParam('options.m_meisyo.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],
];
