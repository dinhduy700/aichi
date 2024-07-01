<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();

$listZaikoHokanryoByNinusiCd = $this->seikyuRepository->getSumKinZaikoHokanryo($request)
                                ->get()->keyBy('ninusi_cd');
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'T', 'row' => 73],
                'size' => 24
                // 'size' => 2
            ],
            'height' => 73,
            'title' => '請　　求　　書  （控）',
            'summary' => [
                'seki_su' => [
                    'start' => ['col' => 'A', 'row' => 62],
                    'end' => ['col' => 'T', 'row' => 63]
                ],
                'nyuko_su' => [
                    'start' => ['col' => 'A', 'row' => 64],
                    'end' => ['col' => 'T', 'row' => 65]
                ],
                'syuko_su' => [
                    'start' => ['col' => 'A', 'row' => 66],
                    'end' => ['col' => 'T', 'row' => 67]
                ],
                'total' => [
                    'start' => ['col' => 'A', 'row' => 70],
                    'end' => ['col' => 'T', 'row' => 71]
                ],
               
            ]
        ],
        'summary' => [
            'seki_su' => [
                ['col' => 'J', 'row' => 1, 'mergeCells' => ['w' => 4, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su'));
                    }

                    return '';
                }],
                ['col' => 'N', 'row' => 1, 'mergeCells' => ['w' => 3, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin'));
                    }

                    return '';
                }],
                ['col' => 'S', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin'));
                    }

                    return '';
                }],
                ['col' => 'E', 'row' => 1, 'value' => '保管料']
            ],
            'nyuko_su' => [
                ['col' => 'J', 'row' => 1, 'mergeCells' => ['w' => 4, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_su'));
                    }

                    return '';
                }],
                ['col' => 'N', 'row' => 1, 'mergeCells' => ['w' => 3, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin'));
                    }

                    return '';
                }],
                ['col' => 'S', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin'));
                    }

                    return '';
                }],
                ['col' => 'E', 'row' => 1, 'value' => '入庫料']
            ],
            'syuko_su' => [
                ['col' => 'J', 'row' => 1, 'mergeCells' => ['w' => 4, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_su'));
                    }

                    return '';
                }],
                ['col' => 'N', 'row' => 1, 'mergeCells' => ['w' => 3, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin'));
                    }

                    return '';
                }],
                ['col' => 'S', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function($page) use($listZaikoHokanryoByNinusiCd){
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        return number_format(data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin'));
                    }

                    return '';
                }],
                ['col' => 'E', 'row' => 1, 'value' => '出庫料']
            ],
            'total' => [
                ['col' => 'N', 'row' => 1, 'mergeCells' => ['w' => 3, 'h' => 2], 'value' => function($page) use ($listZaikoHokanryoByNinusiCd){
                    $totalE = $page->sum('kihon_unchin');
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                       $totalE += data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin');
                    }

                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        $totalE += data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin');
                    }

                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        $totalE += data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin');
                    }
                    return $totalE;

                }],
                ['col' => 'S', 'row' => 1, 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function($page) use ($listZaikoHokanryoByNinusiCd) {
                    $totalE = $page->sum('unchin_gokei');
                    $first      = !empty($page) ? $page->first() : null;
                    $ninusiCd   = !empty($first) ? $first->ninusi_cd : null;
                   
                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                       $totalE += data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin');
                    }

                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        $totalE += data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin');
                    }

                    if ($listZaikoHokanryoByNinusiCd->has($ninusiCd)) {
                        $totalE += data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin');
                    }
                    
                    return $totalE;
                }],
                ['col' => 'E', 'row' => 2, 'value' => '【　　合　　　計　　】']
            ],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'T', 'row' => 21],
            'mergeCells' => [
                ['col' => 'P', 'row' => 1, 'w' => 3, 'h' => 1],
                ['col' => 'J', 'row' => 2, 'w' => 6, 'h' => 2],
                ['col' => 'N', 'row' => 5, 'w' => 4, 'h' => 1],
                ['col' => 'A', 'row' => 18, 'w' => 2, 'h' => 1],
                ['col' => 'C', 'row' => 15, 'w' => 1, 'h' => 2],
                ['col' => 'D', 'row' => 15, 'w' => 2, 'h' => 2],
                ['col' => 'F', 'row' => 15, 'w' => 1, 'h' => 2],
                ['col' => 'G', 'row' => 15, 'w' => 1, 'h' => 2],
                ['col' => 'H', 'row' => 15, 'w' => 3, 'h' => 2],
                ['col' => 'K', 'row' => 15, 'w' => 4, 'h' => 2],
                ['col' => 'O', 'row' => 15, 'w' => 3, 'h' => 2],
                ['col' => 'R', 'row' => 15, 'w' => 2, 'h' => 2],
                ['col' => 'C', 'row' => 17, 'w' => 1, 'h' => 2],
                ['col' => 'D', 'row' => 17, 'w' => 2, 'h' => 2],
                ['col' => 'F', 'row' => 17, 'w' => 1, 'h' => 2],
                ['col' => 'G', 'row' => 17, 'w' => 1, 'h' => 2],
                ['col' => 'H', 'row' => 17, 'w' => 3, 'h' => 2],
                ['col' => 'K', 'row' => 17, 'w' => 4, 'h' => 2],
                ['col' => 'O', 'row' => 17, 'w' => 3, 'h' => 2],
                ['col' => 'R', 'row' => 17, 'w' => 2, 'h' => 2],
                ['col' => 'A', 'row' => 20, 'w' => 1, 'h' => 2],
                ['col' => 'C', 'row' => 20, 'w' => 2, 'h' => 1],
                ['col' => 'C', 'row' => 21, 'w' => 2, 'h' => 1],
                ['col' => 'E', 'row' => 20, 'w' => 5, 'h' => 2],
                ['col' => 'J', 'row' => 20, 'w' => 4, 'h' => 2],
                ['col' => 'N', 'row' => 20, 'w' => 3, 'h' => 2],
                ['col' => 'Q', 'row' => 20, 'w' => 2, 'h' => 2],
                ['col' => 'S', 'row' => 20, 'w' => 1, 'h' => 2],
               
            ],
            'others' => [
                ['col' => 'B', 'row' => 1, 'type' => $exp::DATA_STRING,  'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? '〒' . $first->yubin_no : ' ';
                }],
                ['col' => 'B', 'row' => 2, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? $first->jyusyo1_nm : ' ';
                }],
                ['col' => 'B', 'row' => 3, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? $first->jyusyo2_nm : ' ';
                }],
                ['col' => 'B', 'row' => 4, 'type' => $exp::DATA_STRING, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    return !empty($first) ? $first->ninusi1_nm : ' ';
                }],

                //今回御請求額　　　　
                'konkai_torihiki_kin__top' => ['col' => 'N', 'row' => 5, 'value' => function($page, $pages){
                    $konkaiTorihikiKin = data_get($page, '0.konkai_torihiki_kin') ?? 0;
                    return '￥' . number_format($konkaiTorihikiKin) . '-';
                }],

                //前回ご請求額
                'zenkai_seikyu_kin' => ['col' => 'C', 'row' => 17, 'value' => function($page, $pages) {
                    $zenkaiSeikyuKin = data_get($page, '0.zenkai_seikyu_kin') ?? 0;
                    return request('exp.seikyussk') != 2 ? $zenkaiSeikyuKin : ' ';
                }],
                //御入金額
                'nyukin_kin' => ['col' => 'D', 'row' => 17, 'value' => function($page, $pages) {
                    $genkinKin          = data_get($page, '0.genkin_kin') ?? 0;
                    $furikomiKin        = data_get($page, '0.furikomi_kin') ?? 0;
                    $furikomiTesuryoKin = data_get($page, '0.furikomi_tesuryo_kin') ?? 0;
                    $tegataKin          = data_get($page, '0.tegata_kin') ?? 0;
                    $sonotaNyuKin       = data_get($page, '0.sonota_nyu_kin') ?? 0;
                    
                    $res                = $genkinKin + $furikomiKin + $furikomiTesuryoKin + $tegataKin + $sonotaNyuKin;
                  
                    return request('exp.seikyussk') != 2 ? $res : ' ';
                }],

                //相殺・値引き
                'sousai_kin' => ['col' => 'F', 'row' => 17, 'value' => function($page, $pages) {
                    $sousaiKin = data_get($page, '0.sousai_kin') ?? 0;
                    $nebikiKin = data_get($page, '0.nebiki_kin') ?? 0;

                    $res       = $sousaiKin + $nebikiKin;
                    return request('exp.seikyussk') != 2 ? $res : ' ';
                }],

                //繰越額
                'kjrikosi_kin' => ['col' => 'G', 'row' => 17, 'value' => function($page, $pages) {
                    $kjrikosiKin = data_get($page, '0.kjrikosi_kin') ?? 0;
                    return request('exp.seikyussk') != 2 ? $kjrikosiKin : ' ';
                }],

                // 課税運賃
                'kazei_unchin_kin' => ['col' => 'H', 'row' => 17, 'value' => function($page, $pages) {
                    $kazeiUnchinKin     = data_get($page, '0.kazei_unchin_kin') ?? 0;
                    $kazeiTyukeiKin     = data_get($page, '0.kazei_tyukei_kin') ?? 0;
                    $kazeiTukouryouKin  = data_get($page, '0.kazei_tukouryou_kin') ?? 0;
                    $kazeiNiyakuryoKin  = data_get($page, '0.kazei_niyakuryo_kin') ?? 0;

                    $res = $kazeiUnchinKin + $kazeiTyukeiKin + $kazeiTukouryouKin + $kazeiNiyakuryoKin;

                    return $res;
                }],

                // 消費税等
                'zei_kin' => ['col' => 'K', 'row' => 17, 'value' => function($page, $pages) {
                    $zeiKin = data_get($page, '0.zei_kin') ?? 0;
                    return $zeiKin;
                }],

                // 非課税額
                'hikazei_kin' => ['col' => 'O', 'row' => 17, 'value' => function($page, $pages) {
                    $hikazeiUnchinKin       = data_get($page, '0.hikazei_unchin_kin') ?? 0;
                    $hikazeiTyukeiKin       = data_get($page, '0.hikazei_tyukei_kin') ?? 0;
                    $hikazeiTukouryoKin     = data_get($page, '0.hikazei_tukouryo_kin') ?? 0;
                    $hikazeiNiyakuryoKin    = data_get($page, '0.hikazei_niyakuryo_kin') ?? 0;

                    $res = $hikazeiUnchinKin + $hikazeiTyukeiKin + $hikazeiTukouryoKin + $hikazeiNiyakuryoKin;

                    return $res;
                }],

                // 今回取引額
                'konkai_torihiki_kin' => ['col' => 'R', 'row' => 17, 'value' => function($page, $pages) {
                    $konkaiTorihikiKin = data_get($page, '0.konkai_torihiki_kin') ?? 0;
                    return $konkaiTorihikiKin;
                }],
                
                ['col' => 'A', 'row' => 18, 'value' => function($page) {
                    $first = !empty($page) ? $page->first() : null;
                    $date = Illuminate\Support\Carbon::parse($first->seikyu_sime_dt);
                    return $date->format('Y年m月d日') . '締 ';
                }],

                ['col' => 'S', 'row' => 1, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE_ON_TOTAL_GROUP],

                ['col' => 'P', 'row' => 1, 'value' => function($page) {
                    return !empty(request('exp.hakkou_dt')) ? App\Helpers\Formatter::date(request('exp.hakkou_dt')) : '';
                }],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 22],
            'end' => ['col' => 'T', 'row' => 23],
        ],
        'others' => [
           
        ],
    ],
  
    'block' => [
        [   // row1
            'A' => ['field' => 'unso_dt', 'mergeCells' => ['w' => 1, 'h' => 2], 'value' => function($row) {
                return \App\Helpers\Formatter::datetime(data_get($row, 'unso_dt'), 'm/d');
            }],
            'B' => ['field' => 'syaban'],
            'C' => ['field' => 'hatuti_nm', 'mergeCells' => ['w'=> 2, 'h' => 1]],
            'E' => ['field' => 'hinmei_nm'],
            'J' => ['field' => 'su', 'mergeCells' => ['w' => 2, 'h' => 2]],
            'L' => ['field' => 'tani_nm', 'mergeCells' => ['w'=>2, 'h'=>2]],
            'N' => ['field' => 'kihon_unchin', 'mergeCells' => ['w'=>3, 'h'=>2]],
            'Q' => ['field' => 'tukoryo_kin', 'mergeCells' => ['w'=>2, 'h'=>2]],
            'S' => ['field' => 'unchin_gokei', 'mergeCells' => ['w'=>1, 'h'=>2]],
        ],
        [   // row2
            'B' => ['field' => 'syasyu'],
            'C' => ['field' => 'hachaku_nm', 'mergeCells' => ['w' => 2 , 'h' => 1]],
        ],
    ]
];