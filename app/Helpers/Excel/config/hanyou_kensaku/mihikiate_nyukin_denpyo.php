<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 2],
            'end' => ['col' => 'D', 'row' => 2],
        ],
    ],
    'block' => [
        'A' => ['field' => 'ninusi_cd', 'type' =>$exp::DATA_STRING],
        'B' => ['field' => 'ninusi1_nm'],
        'C' => ['field' => 'nyukin_no'],
        'D' => ['field' => 'nyukin_gaku'],
    ],
];
