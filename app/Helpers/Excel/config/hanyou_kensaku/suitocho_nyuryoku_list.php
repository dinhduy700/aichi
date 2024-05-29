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
        'A' => ['field' => 'kaisyu_dt', 'type' => $exp::DATA_DATETIME],
        'B' => ['field' => 'syaban'],
        'C' => ['field' => 'jyomuin_nm'],
        'D' => ['field' => 'field_no4'],
        'E' => ['field' => 'bumon_cd'],
        'F' => ['field' => 'bumon_nm'],
        'G' => ['field' => 'ninusi_cd'],
        'H' => ['field' => 'ninusi1_nm'],
        'I' => ['field' => 'gyosya_cd'],
        'J' => ['field' => 'gyosya_nm'],
        'K' => ['field' => 'total_1'],
        'L' => ['field' => 'total_2'],
        'M' => ['field' => 'haitatu_dt', 'type' => $exp::DATA_DATETIME],
    ],
];
