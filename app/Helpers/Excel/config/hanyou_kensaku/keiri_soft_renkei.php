<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 2],
            'end' => ['col' => 'S', 'row' => 2],
        ],
    ],
    'block' => [
        'A' => ['field' => 'unso_dt', 'type' => $exp::DATA_DATETIME],
        'B' => ['field' => 'field_no2'],
        'C' => ['field' => 'bumon_cd'],
        'D' => ['field' => 'bumon_nm'],
        'E' => ['field' => 'ninusi_cd'],
        'F' => ['field' => 'ninusi_ryaku_nm'],

        'G' => ['field' => 'field_no7'],
        'H' => ['field' => 'atena'],
        'I' => ['field' => 'field_no9'],
        'J' => ['field' => 'field_no10'],
        'K' => ['field' => 'syaban'],
        'L' => ['field' => 'jyomuin_cd'],
        'M' => ['field' => 'jyomuin_nm'],
        'N' => ['field' => 'tukoryo_kin'],
    ],
];
