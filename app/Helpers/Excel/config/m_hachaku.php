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
            'end' => ['col' => 'M', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'hachaku_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'hachaku_nm'],
        'D' => ['field' => 'atena_ninusi_id'],
        'E' => ['field' => 'atena_ninusi_nm'],
        'F' => ['field' => 'atena'],
        'G' => ['field' => 'jyusyo1_nm'],
        'H' => ['field' => 'jyusyo2_nm'],
        'I' => ['field' => 'tel'],
        'J' => ['field' => 'fax'],
        'K' => ['field' => 'ninusi_id'],
        'L' => ['field' => 'ninusi_nm'],
        'M' => ['field' => 'kyumin_flg', 'value' => function($row) use($formatKbn) { return $formatKbn($row, 'kyumin_flg'); }],
    ],

];