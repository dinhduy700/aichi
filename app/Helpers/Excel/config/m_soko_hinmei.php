<?php 

$formatKbn = function($row, $key) {
    return data_get(
        configParam('options.m_soko_hinmei.' . $key, [], 1),
        data_get($row, $key, ''),
    );
};


return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'AA', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'ninusi_cd'],
        'B' => ['field' => 'ninusi_nm'],
        'C' => ['field' => 'hinmei_cd'],
        'D' => ['field' => 'kana'],
        'E' => ['field' => 'hinmei_nm'],
        'F' => ['field' => 'kikaku'],
        'G' => ['field' => 'ondo', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'ondo'); }],
        'H' => ['field' => 'zaiko_kbn', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'zaiko_kbn'); }],
        'I' => ['field' => 'case_cd'],
        'J' => ['field' => 'case_nm'],
        'K' => ['field' => 'irisu'],
        'L' => ['field' => 'hasu_kiriage'],
        'M' => ['field' => 'bara_tani'],
        'N' => ['field' => 'bara_tani_nm'],
        'O' => ['field' => 'bara_tani_juryo'],
        'P' => ['field' => 'uke_tanka'],
        'Q' => ['field' => 'seikyu_hinmei_cd'],
        'R' => ['field' => 'seikyu_hinmei_nm'],
        'S' => ['field' => 'keisan_kb', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'keisan_kb'); }],
        'T' => ['field' => 'seikyu_keta', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'seikyu_keta'); }],
        'U' => ['field' => 'seikyu_bunbo'],
        'V' => ['field' => 'nieki_nyuko_tanka'],
        'W' => ['field' => 'nieki_syuko_tanka'],
        'X' => ['field' => 'hokanryo_kin'],
        'Y' => ['field' => 'bumon_cd'],
        'Z' => ['field' => 'bumon_nm'],
        'AA' => ['field' => 'kyumin_flg', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kyumin_flg'); }],
    ],

];