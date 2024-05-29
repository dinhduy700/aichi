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
        'A' => ['field' => 'nipou_dt', 'type' => $exp::DATA_DATETIME],
        'B' => ['field' => 'syaban'],
        'C' => ['field' => 'jyomuin_nm'],
        'D' => ['field' => 'ninusi_ryaku_nm'],
        'E' => ['field' => 'syubetu_cd'],
        'F' => ['field' => 'gyosya_nm'],

        'G' => ['field' => 'hatuti_nm'],
        'H' => ['field' => 'hachaku_nm'],
        'I' => ['field' => 'hinmoku_nm'],
        'J' => ['field' => 'hinmei_nm'],
        'K' => ['field' => 'su'],
        'L' => ['field' => 'tani_nm'],
        'M' => ['field' => 'unchin_kin'],

        'N' => ['field' => 'tukoryo_kin'],
        'O' => ['field' => 'tesuryo_kin'],
        'P' => ['field' => 'tyukei_kin'],

        'Q' => ['field' => 'syuka_kin'],
        'R' => ['field' => 'unten_kin'],
        'S' => ['field' => 'kaisyu_kin']
    ],
];
