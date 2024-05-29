<?php

$format = new App\Helpers\Formatter();
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'D', 'row' => 4],
        ],
        'others' => [
            ['col' => 'E', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'user_cd'],
        'B' => ['field' => 'group', 'value' => function ($row) {
            return data_get(
                configParam('options.m_user.group', [], 1),
                data_get($row, 'group', ''),
                ''
            );
        }],
        'C' => ['field' => 'biko'],
        'D' => ['field' => 'kyumin_flg', 'value' => function ($row) {
            return data_get(
                configParam('options.m_user.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],
];
