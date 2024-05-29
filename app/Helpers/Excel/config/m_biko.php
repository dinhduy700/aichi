<?php 
$formatKbn = function($row, $key) {
    return data_get(
        configParam('options.m_biko.' . $key, [], 1),
        data_get($row, $key, ''),
    );
};

return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'E', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'biko_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'biko_nm'],
        'D' => ['field' => 'syubetu_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'syubetu_kbn'); }],
        'E' => ['field' => 'kyumin_flg', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kyumin_flg'); }],
    ],

];