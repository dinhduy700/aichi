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
        'A' => ['field' => 'nipou_dt' , 'type' => $exp::DATA_DATETIME],
        'B' => ['field' => 'yousya_ryaku_nm'],
        'C' => ['field' => 'ninusi_ryaku_nm'],
        'D' => ['field' => 'syubetu_nm'],
        'E' => ['field' => 'gyosya_nm'],
        'F' => ['field' => 'hatuti_nm'],
        'G' => ['field' => 'hachaku_nm'],
        'H' => ['field' => 'hinmoku_nm'],
        'I' => ['field' => 'hinmei_nm'],
        'J' => ['field' => 'su'],
        'K' => ['field' => 'tani_nm'],
        'L' => ['field' => 'unchin_kin'],
        'M' => ['field' => 'tukoryo_kin'],
        'N' => ['field' => 'tesuryo_kin'],
        'O' => ['field' => 'tyukei_kin'],
        'P' => ['field' => 'syuka_kin'],
        'Q' => ['field' => 'yosya_tyukei_kin'],
        'R' => ['field' => 'yosya_tukoryo_kin'],
        'S' => ['field' => 'total']

    ],
];
