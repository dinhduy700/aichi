<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 2],
            'end' => ['col' => 'Q', 'row' => 2],
        ],
    ],
    'block' => [
        'A' => ['field' => 'seikyu_sime_dt', 'type'=>$exp::DATA_DATETIME],
        'B' => ['field' => 'ninusi_cd'],
        'C' => ['field' => 'ninusi1_nm'],
        'D' => ['field' => 'unso_dt', 'type'=>$exp::DATA_DATETIME],
        'E' => ['field' => 'syaban', 'type'=>$exp::DATA_STRING],
        'F' => ['field' => 'jyomuin_nm'],
        'G' => ['field' => 'hatuti_nm'],
        'H' => ['field' => 'hachaku_nm'],
        'I' => ['field' => 'hinmei_nm'],
        'J' => ['field' => 'su'],
        'K' => ['field' => 'tani_nm'],
        'L' => ['field' => 'unchin_kin'],
        'M' => ['field' => 'tyukei_kin'],
        'N' => ['field' => 'tukoryo_kin'],
        'O' => ['field' => 'uriage_den_no'],
        'P' => ['field' => 'add_tanto_cd'],
        'Q' => ['field' => 'add_tanto_nm'],
    ],
];
