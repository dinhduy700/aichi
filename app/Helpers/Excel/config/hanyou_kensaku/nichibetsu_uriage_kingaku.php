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
        'A' => ['field' => 'ninusi_cd'],
        'B' => ['field' => 'ninusi1_nm'],
        'C' => ['field' => 'haitatu_dt', 'type' => $exp::DATA_DATETIME],
        'D' => ['field' => 'unchin_kin'],
        'E' => ['field' => 'tyukei_kin'],
        'F' => ['field' => 'syuka_kin'],
        'G' => ['field' => 'tesuryo_kin'],
        'H' => ['field' => 'unten_kin']
    ],
];
