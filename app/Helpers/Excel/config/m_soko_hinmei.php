<?php 

return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 6],
            'end' => ['col' => 'M', 'row' => 8],
        ],
        'others' => [
            ['col' => 'M', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'ninusi_cd'],
            'B' => ['field' => 'ninusi_nm'],
            'C' => ['field' => 'hinmei_cd'],
            'D' => ['field' => 'hinmei_nm'],
            'G' => ['field' => 'case_cd'],
            'H' => ['field' => 'case_nm'],
            'I' => ['field' => 'irisu'],
            'J' => ['field' => 'hasu_kiriage'],
            'K' => ['field' => 'uke_tanka'],
            'L' => ['field' => 'seikyu_keta'],
            'M' => ['field' => 'nieki_nyuko_tanka'],
        ],
        [   // row2
            'D' => ['field' => 'kikaku'],
            'E' => ['field' => 'ondo'],
            'F' => ['field' => 'keisan_kb'],
            'G' => ['field' => 'bara_tani'],
            'H' => ['field' => 'bara_tani_nm'],
            'I' => ['field' => 'bara_tani_juryo'],
            'K' => ['field' => 'hokanryo_kin'],
            'L' => ['field' => 'seikyu_bunbo'],
            'M' => ['field' => 'nieki_syuko_tanka'],
        ],
        [
            'D' => ['field' => 'kana'],
            'E' => ['field' => 'zaiko_kbn'],
            'F' => ['field' => 'seikyu_hinmei_nm'],
        ],
    ],

];