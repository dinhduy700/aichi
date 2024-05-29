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
        'A' => ['field' => 'field_no1'],
        'B' => ['field' => 'field_no2'],
        'C' => ['field' => 'gyosya_nm'],
        'E' => ['field' => 'hachaku_nm'],
        'F' => ['field' => 'field_no6'],
        'G' => ['field' => 'jyomuin_nm'],
        'H' => ['field' => 'haitatu_dt', 'type' => $exp::DATA_DATETIME]
    ],
];
