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
        'A' => ['field' => 'syaban'],
        'B' => ['field' => 'jyomuin_cd'],
        'C' => ['field' => 'jyomuin_nm'],
        'D' => ['field' => 'haitatu_dt', 'type' => $exp::DATA_DATETIME],
        'E' => ['field' => 'jikoku'],
        'F' => ['field' => 'ninusi_ryaku_nm'],

        'G' => ['field' => 'hachaku_nm'],
        'H' => ['field' => 'hinmoku_nm'],
        'I' => ['field' => 'syubetu_nm'],
        'J' => ['field' => 'su'],
        'K' => ['field' => 'hinmei_nm'],
        'L' => ['field' => 'syuka_dt', 'type' => $exp::DATA_DATETIME],
        'M' => ['field' => 'sitadori'],

        'N' => ['field' => 'jyotai'],
        'O' => ['field' => 'gyosya_nm'],
        'P' => ['field' => 'unchin_kin'],

        'Q' => ['field' => 'tyukei_kin'],
        'R' => ['field' => 'syuka_kin'],
        'S' => ['field' => 'tesuryo_kin'],
        'T' => ['field' => 'unten_kin'],
        'U' => ['field' => 'biko'],
        'V' => ['field' => 'uriage_den_no']
    ],
];
