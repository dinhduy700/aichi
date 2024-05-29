<?php 

$formatKbn = function($row, $key) {
    return data_get(
        configParam('options.m_yousya.' . $key, [], 1),
        data_get($row, $key, ''),
    );
};


return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'AK', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'yousya_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'yousya1_nm'],
        'D' => ['field' => 'yousya2_nm'],
        'E' => ['field' => 'yousya_ryaku_nm'],
        'F' => ['field' => 'bumon_cd'],
        'G' => ['field' => 'bumon_nm'],
        'H' => ['field' => 'yubin_no'],
        'I' => ['field' => 'jyusyo1_nm'],
        'J' => ['field' => 'jyusyo2_nm'],
        'K' => ['field' => 'tel'],
        'L' => ['field' => 'fax'],
        'M' => ['field' => 'siharai_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'siharai_kbn'); }],
        'N' => ['field' => 'siharai_cd'],
        'O' => ['field' => 'siharai_nm'],
        'P' => ['field' => 'yousya_ritu'],
        'Q' => ['field' => 'siharai_umu_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'siharai_umu_kbn'); }],
        'R' => ['field' => 'simebi1'],
        'S' => ['field' => 'simebi2'],
        'T' => ['field' => 'simebi3'],
        'U' => ['field' => 'mikakutei_seigyo_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'mikakutei_seigyo_kbn'); }],
        'V' => ['field' => 'kin_hasu_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kin_hasu_kbn'); }],
        'W' => ['field' => 'kin_hasu_tani', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kin_hasu_tani'); }],
        'X' => ['field' => 'zei_keisan_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'zei_keisan_kbn'); }],
        'Y' => ['field' => 'zei_hasu_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'zei_hasu_kbn'); }],
        'Z' => ['field' => 'zei_hasu_tani', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'zei_hasu_tani'); }],
        'AA' => ['field' => 'kaikake_saki_cd'],
        'AB' => ['field' => 'kaikake_saki_nm'],
        'AC' => ['field' => 'siharai_nyuryoku_umu_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'siharai_nyuryoku_umu_kbn'); }],
        'AD' => ['field' => 'siharai1_dd'],
        'AE' => ['field' => 'siharai2_dd'],
        'AF' => ['field' => 'comennt'],
        'AG' => ['field' => 'kensaku_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kensaku_kbn'); }],
        'AH' => ['field' => 'mail'],
        'AI' => ['field' => 'haisya_biko'],
        'AJ' => ['field' => 'biko'],
        'AK' => ['field' => 'kyumin_flg', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kyumin_flg'); }],
    ],

];