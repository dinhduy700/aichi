<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();

return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'AO', 'row' => 56],
                'size' => 16
            ],
            'height' => 56,
            'title' => '請　　求　　書  （控）',
            'summary' => [
                'total' => [
                    'start' => ['col' => 'A', 'row' => 54],
                    'end' => ['col' => 'AO', 'row' => 55]
                ],
            ]
        ],
        'summary' => [
            'total' => [
                ['col' => 'AF', 'row' => 2, 'mergeCells' => ['w' => 3, 'h' => 1], 'value' => function($page) {
                    return $page->sum('kihon_unchin');
                }],
                ['col' => 'AL', 'row' => 2, 'mergeCells' => ['w' => 3, 'h' => 1], 'value' => function($page) {
                    return $page->sum('unchin_gokei');
                }],
                ['col' => 'R', 'row' => 2, 'value' => '【　合　計　】']
            ],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'AO', 'row' => 21],
            'mergeCells' => [
                ['col' => 'I', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'M', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'Q', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'U', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'Y', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'AC', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'AG', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'AK', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'I', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'M', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'Q', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'U', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'Y', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'AC', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'AG', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'AK', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'B', 'row' => 20, 'w' => 2, 'h' => 2],
                ['col' => 'D', 'row' => 20, 'w' => 5, 'h' => 1],
                ['col' => 'D', 'row' => 21, 'w' => 5, 'h' => 1],
                ['col' => 'I', 'row' => 20, 'w' => 7, 'h' => 1],
                ['col' => 'I', 'row' => 21, 'w' => 7, 'h' => 1],
                ['col' => 'P', 'row' => 20, 'w' => 11, 'h' => 2],
                ['col' => 'AA', 'row' => 20, 'w' => 5, 'h' => 2],
                ['col' => 'AF', 'row' => 20, 'w' => 3, 'h' => 2],
                ['col' => 'AI', 'row' => 20, 'w' => 3, 'h' => 2],
                ['col' => 'AL', 'row' => 20, 'w' => 3, 'h' => 2],
            ],
            'others' => [
                ['col' => 'D', 'row' => 2, 'type' => $exp::DATA_STRING, 'height' => 15,  'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? '〒' . $first->yubin_no : ' ';
                }],
                ['col' => 'D', 'row' => 3, 'type' => $exp::DATA_STRING, 'height' => 15, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? $first->jyusyo1_nm : ' ';
                }],
                ['col' => 'D', 'row' => 4, 'type' => $exp::DATA_STRING, 'height' => 15, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? $first->jyusyo2_nm : ' ';
                }],
                ['col' => 'D', 'row' => 5, 'type' => $exp::DATA_STRING, 'height' => 15, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? $first->ninusi1_nm : ' ';
                }],

                //今回御請求額　　　　
                'konkai_torihiki_kin__top' => ['col' => 'AE', 'row' => 5, 'value' => function($page, $pages){
                    $konkaiTorihikiKin = data_get($page, '0.konkai_torihiki_kin') ?? 0;
                    return '￥' . number_format($konkaiTorihikiKin);
                }],

                //前回ご請求額
                'zenkai_seikyu_kin' => ['col' => 'I', 'row' => 17, 'value' => function($page, $pages) {
                    $zenkaiSeikyuKin = data_get($page, '0.zenkai_seikyu_kin') ?? 0;
                    return request('exp.seikyussk') != 2 ? $zenkaiSeikyuKin : ' ';
                }],
                //御入金額
                'nyukin_kin' => ['col' => 'M', 'row' => 17, 'value' => function($page, $pages) {
                    $genkinKin          = data_get($page, '0.genkin_kin') ?? 0;
                    $furikomiKin        = data_get($page, '0.furikomi_kin') ?? 0;
                    $furikomiTesuryoKin = data_get($page, '0.furikomi_tesuryo_kin') ?? 0;
                    $tegataKin          = data_get($page, '0.tegata_kin') ?? 0;
                    $sonotaNyuKin       = data_get($page, '0.sonota_nyu_kin') ?? 0;
                    
                    $res                = $genkinKin + $furikomiKin + $furikomiTesuryoKin + $tegataKin + $sonotaNyuKin;
                  
                    return request('exp.seikyussk') != 2 ? $res : ' ';
                }],

                //相殺・値引き
                'sousai_kin' => ['col' => 'Q', 'row' => 17, 'value' => function($page, $pages) {
                    $sousaiKin = data_get($page, '0.sousai_kin') ?? 0;
                    $nebikiKin = data_get($page, '0.nebiki_kin') ?? 0;

                    $res       = $sousaiKin + $nebikiKin;
                    return request('exp.seikyussk') != 2 ? $res : ' ';
                }],

                //繰越額
                'kjrikosi_kin' => ['col' => 'U', 'row' => 17, 'value' => function($page, $pages) {
                    $kjrikosiKin = data_get($page, '0.kjrikosi_kin') ?? 0;
                    return request('exp.seikyussk') != 2 ? $kjrikosiKin : ' ';
                }],

                // 課税運賃
                'kazei_unchin_kin' => ['col' => 'Y', 'row' => 17, 'value' => function($page, $pages) {
                    $kazeiUnchinKin     = data_get($page, '0.kazei_unchin_kin') ?? 0;
                    $kazeiTyukeiKin     = data_get($page, '0.kazei_tyukei_kin') ?? 0;
                    $kazeiTukouryouKin  = data_get($page, '0.kazei_tukouryou_kin') ?? 0;
                    $kazeiNiyakuryoKin  = data_get($page, '0.kazei_niyakuryo_kin') ?? 0;

                    $res = $kazeiUnchinKin + $kazeiTyukeiKin + $kazeiTukouryouKin + $kazeiNiyakuryoKin;

                    return $res;
                }],

                // 消費税等
                'zei_kin' => ['col' => 'AC', 'row' => 17, 'value' => function($page, $pages) {
                    $zeiKin = data_get($page, '0.zei_kin') ?? 0;
                    return $zeiKin;
                }],

                // 非課税額
                'hikazei_kin' => ['col' => 'AG', 'row' => 17, 'value' => function($page, $pages) {
                    $hikazeiUnchinKin       = data_get($page, '0.hikazei_unchin_kin') ?? 0;
                    $hikazeiTyukeiKin       = data_get($page, '0.hikazei_tyukei_kin') ?? 0;
                    $hikazeiTukouryoKin     = data_get($page, '0.hikazei_tukouryo_kin') ?? 0;
                    $hikazeiNiyakuryoKin    = data_get($page, '0.hikazei_niyakuryo_kin') ?? 0;

                    $res = $hikazeiUnchinKin + $hikazeiTyukeiKin + $hikazeiTukouryoKin + $hikazeiNiyakuryoKin;

                    return $res;
                }],

                // 今回取引額
                'konkai_torihiki_kin' => ['col' => 'AK', 'row' => 17, 'value' => function($page, $pages) {
                    $konkaiTorihikiKin = data_get($page, '0.konkai_torihiki_kin') ?? 0;
                    return $konkaiTorihikiKin;
                }],
                
                ['col' => 'B', 'row' => 18, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? App\Helpers\Formatter::dateJP($first->seikyu_sime_dt, 'y年m月d日(w)') : ' ';
                }],

                ['col' => 'AL', 'row' => 1, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE],

                ['col' => 'AH', 'row' => 1, 'value' => function($page) {
                    return !empty(request('exp.hakkou_dt')) ? App\Helpers\Formatter::date(request('exp.hakkou_dt')) : '';
                }],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 22],
            'end' => ['col' => 'AO', 'row' => 23],
        ],
        'others' => [
           
        ],
    ],
  
    'block' => [
        [   // row1
            'B' => ['field' => 'unso_dt', 'type' => $exp::DATA_DATETIME,'mergeCells' => ['w'=>2, 'h'=>1]],
            'D' => ['field' => 'syaban', 'mergeCells' => ['w'=>5, 'h'=>1]],
            'I' => ['field' => 'hatuti_nm'],
            'P' => ['field' => 'hinmei_nm'],
            'AD' => ['field' => 'tani_nm'],
            'AA' => ['field' => 'su', 'mergeCells' => ['w'=>3, 'h'=>1]],
            'AD' => ['field' => 'tani_nm', 'mergeCells' => ['w'=>2, 'h'=>1]],
        ],
        [   // row2
            'D' => ['field' => 'syasyu', 'type' => $exp::DATA_STRING],
            'I' => ['field' => 'hachaku_nm', 'mergeCells' => ['w'=>7, 'h'=>1]],
            'AF' => ['field' => 'kihon_unchin', 'mergeCells' => ['w'=>3, 'h'=>1]],
            'AI' => ['field' => 'tukoryo_kin', 'mergeCells' => ['w'=>3, 'h'=>1]],
            'AL' => ['field' => 'unchin_gokei', 'mergeCells' => ['w'=>3, 'h'=>1]],
        ],
    ]
];