<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 2],
            'end' => ['col' => 'F', 'row' => 2],
        ],
    ],
    'block' => [
        'A' => ['field' => 'ninusi_cd'],
        'B' => ['field' => 'ninusi1_nm'],
        'C' => ['field' => 'seikyu_sime_dt', 'type' => $exp::DATA_DATETIME],
        'D' => ['field' => 'konkai_torihiki_kin'],
        'E' => ['field' => 'total_no5'],
        'F' => ['field' => 'total_no6'],
    ],
];
