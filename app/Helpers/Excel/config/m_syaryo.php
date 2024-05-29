<?php

$format = new App\Helpers\Formatter();
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'W', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'syaryo_cd'],
        'B' => ['field' => 'syasyu_cd'],
        'C' => ['field' => 'syasyu_nm'],
        'D' => ['field' => 'jiyo_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_syaryo.jiyo_kbn', [], 1),
                data_get($row, 'jiyo_kbn', ''),
                ''
            );
        }],
        'E' => ['field' => 'jyomuin_cd'],
        'F' => ['field' => 'jyomuin_nm'],
        'G' => ['field' => 'yousya_cd'],
        'H' => ['field' => 'yousya_nm'],
        'I' => ['field' => 'bumon_cd'],
        'J' => ['field' => 'bumon_nm'],
        'K' => ['field' => 'sekisai_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_syaryo.sekisai_kbn', [], 1),
                data_get($row, 'sekisai_kbn', ''),
                ''
            );
        }],
        'L' => ['field' => 'sekisai_jyuryo', 'value' => function ($row) use ($format) {
            return $format->numberFormat(data_get($row, 'sekisai_jyuryo', ''), -1);
        }],
        'M' => ['field' => 'point', 'value' => function ($row) use ($format) {
            return $format->numberFormat(data_get($row, 'point', ''), -1);
        }],
        'N' => ['field' => 'himoku_ritu', 'value' => function ($row) use ($format) {
            return $format->numberFormat(data_get($row, 'himoku_ritu', ''), -1);
        }],
        'O' => ['field' => 'haisya_dt', 'value' => function ($row) use ($format) {
            $haisyaDt = data_get($row, 'haisya_dt', '');
            return $format->date($haisyaDt);
        }],
        'P' => ['field' => 'rikuun_cd'],
        'Q' => ['field' => 'rikuun_nm'],
        'R' => ['field' => 'car_number_syubetu'],
        'S' => ['field' => 'car_number_kana'],
        'T' => ['field' => 'car_number'],
        'U' => ['field' => 'haisya_biko'],
        'V' => ['field' => 'biko'],
        'W' => ['field' => 'kyumin_flg', 'value' => function ($row) {
            return data_get(
                configParam('options.m_syaryo.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],

];
