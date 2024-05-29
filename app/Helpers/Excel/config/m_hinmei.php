<?php 
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'Q', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'hinmei_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'hinmei_nm'],
        'D' => ['field' => 'hinmei2_cd'],
        'E' => ['field' => 'hinmoku_cd'],
        'F' => ['field' => 'hinmoku_nm'],
        'G' => ['field' => 'tani_cd'],
        'H' => ['field' => 'tani_nm'],
        'I' => ['field' => 'tani_jyuryo'],
        'J' => ['field' => 'haisya_tani_jyuryo'],
        'K' => ['field' => 'syoguti_kbn1', 'value' => function($row) {
            return data_get(
                configParam('options.m_hinmei.syoguti_kbn1', [], 1),
                data_get($row, 'syoguti_kbn1', ''),
                ''
            );
        }],
        'L' => ['field' => 'syoguti_kbn2', 'value' => function($row) {
            return data_get(
                configParam('options.m_hinmei.syoguti_kbn2', [], 1),
                data_get($row, 'syoguti_kbn2', ''),
                ''
            );
        }],
        'M' => ['field' => 'ninusi_id'],
        'N' => ['field' => 'ninusi_nm'],
        'O' => ['field' => 'bumon_cd'],
        'P' => ['field' => 'bumon_nm'],
        'Q' => ['field' => 'kyumin_flg', 'value' => function($row) {
            return data_get(
                configParam('options.m_hinmei.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],

];