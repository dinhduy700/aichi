<?php

return $menus = [
    'my-menu' => ['label' => getPageTitle('my_menu.index'), 'href' => route('my_menu.index')],
    'haisha' => [
        'label' => '配車支援', 'href' => null,
        'sub' => [
            '受注入力' => [
                'label' => getPageTitle('jyutyu.order_entry.index'),
                'sub' => [
                    '受注モード' => ['label' => '受注モード', 'href' => route('jyutyu.order_entry.index')],
                    '配車モード' => ['label' => '配車モード', 'href' => route('order.order_entry.dispatch')]
                ]
            ],
            '受注リスト' => ['label' => getPageTitle('jyutyu.exp.filterForm'), 'href' => route('jyutyu.exp.filterForm')],
            '作業指示書' => ['label' => getPageTitle('sagyo.exp.filterForm'), 'href' => route('sagyo.exp.filterForm')],
        ],
    ],
    'uriage' => [
        'label' => '売上管理', 'href' => null,
        'sub' => [
            '売上入力' => ['label' => getPageTitle('uriage.uriage_entry.index'), 'href' => route('uriage.uriage_entry.index')],
            '売上一覧表' => ['label' => getPageTitle($nm = 'uriage.exp.filterForm'), 'href' => route($nm)],
            '納品書・受領書・出庫伝票・出庫伝票控え' => ['label' => getPageTitle($nm = 'uriage.nouhinsyo.filterForm'), 'href' => route($nm)],
            '請求支払' => [
                'label' => '請求支払',
                'sub' => [
                    '請求締日選択' => ['label' => getPageTitle('seikyu.seikyu_shimebi.index'), 'href' => route('seikyu.seikyu_shimebi.index')],
                    '請求書' => ['label' => getPageTitle('seikyu.seikyu_sho.exp.index'), 'href' => route('seikyu.seikyu_sho.exp.index')],
                    '運賃未確定一覧表' => ['label' => getPageTitle($nm = 'seikyu.mikakutei.index'), 'href' => route($nm)],
                    '請求一覧表' => ['label' => getPageTitle($nm = 'seikyu.list.index'), 'href' => route($nm)],
                    '請求確定処理' => ['label' => getPageTitle($nm = 'seikyu.kakutei.index'), 'href' => route($nm)],
                ],
            ],
            '売掛・買掛' => [
                'label' => '売掛・買掛',
                'sub' => [
                    '入金入力' => ['label' => getPageTitle('nyukin.index'), 'href' => route('nyukin.index')],
                    '入金一覧表' => ['label' => getPageTitle($nm = 'nyukin.exp.filterForm'), 'href' => route($nm)],
                ],
            ],
        ],
    ],
    '倉庫管理' => [
        'label' => '倉庫管理', 'href' => null,
        'sub' => [
            '入出庫入力' => ['label' => getPageTitle('nyusyuko.nyuryoku.index'), 'href' => route('nyusyuko.nyuryoku.index')],
            '入出庫日報' => ['label' => getPageTitle($nm = 'nyusyuko.nipou.nipouFilterForm'), 'href' => route($nm)],
            '在庫報告書' => ['label' => getPageTitle($nm = 'nyusyuko.exp.zaikoFilterForm'), 'href' => route($nm)],
            '在庫一覧表' => ['label' => getPageTitle($nm = 'nyusyuko.zaikoList.zaikoListFilterForm'), 'href' => route($nm)],
            '総量ﾋﾟｯｷﾝｸﾞﾘｽﾄ' => ['label' => getPageTitle($nm = 'picking.soryo.exp.filterForm'), 'href' => route($nm)],
            'ピッキングリスト' => ['label' => getPageTitle($nm = 'picking.picking_gurisuto.exp.filterForm'), 'href' => route($nm)],
            //'荷札発行' => ['label' => '荷札発行'],
            '請求業務' => [
                'label' => '請求業務',
                'sub' => [
                    //'保管料・荷役料請求計算' => ['label' => '保管料・荷役料請求計算'],
                    '保管料・荷役料請求計算書' => ['label' => getPageTitle($nm = 'hokanryo.niyakuryo.filterForm'), 'href' => route($nm)],
                ],
            ],
            '在庫管理' => [
                'label' => '在庫管理',
                'sub' => [
                    '在庫照会' => ['label' => getPageTitle('zaiko_shoukai.index'), 'href' => route('zaiko_shoukai.index')],
                    '棚卸記入表' => ['label' => getPageTitle('tanaorosi.exp.filterForm'), 'href' => route('tanaorosi.exp.filterForm')],
                    '受払元帳' => ['label' => getPageTitle('uketsuke_haraicho.exp.filterForm'), 'href' => route('uketsuke_haraicho.exp.filterForm')],
                    '商品受払一覧表' => ['label' => getPageTitle('shouhin_ukebarai.exp.filterForm'), 'href' => route('shouhin_ukebarai.exp.filterForm')],
                ],
            ],
        ],
    ],
    'マスタ保守' => [
        'label' => 'マスタ保守', 'href' => null,
        'sub' => [
            '基本マスタ' => [
                'label' => '基本マスタ',
                'sub' => [
                    'm_bumon' => ['label' => getPageTitle('master.bumon.index'), 'href' => route('master.bumon.index')],
                    'm_jyomuin' => ['label' => getPageTitle('master.jyomuin.index'), 'href' => route('master.jyomuin.index')],
                    'm_ninusi' => ['label' => getPageTitle('master.ninusi.index'), 'href' => route('master.ninusi.index')],
                    'm_yousya' => ['label' => getPageTitle('master.yousya.index'), 'href' => route('master.yousya.index')],
                    'm_syaryo' => ['label' => getPageTitle('master.syaryo.index'), 'href' => route('master.syaryo.index')],
                    //'車種メンテ' => ['label' => '車種メンテ'],
                    'm_hachaku' => ['label' => getPageTitle('master.hachaku.index'), 'href' => route('master.hachaku.index')],
                    'm_hinmei' => ['label' => getPageTitle('master.hinmei.index'), 'href' => route('master.hinmei.index')],
                    'm_hinmoku' => ['label' => getPageTitle('master.hinmoku.index'), 'href' => route('master.hinmoku.index')],
                    'm_biko' => ['label' => getPageTitle('master.biko.index'), 'href' => route('master.biko.index')],
                    //'単位メンテ' => ['label' => '単位メンテ'],
                    'm_meisyo' => ['label' => getPageTitle('master.meisyo.index'), 'href' => route('master.meisyo.index')],
                    'm_user' => ['label' => getPageTitle('master.user.index'), 'href' => route('master.user.index')],
                ],
            ],
            '倉庫管理' => [
                'label' => '倉庫管理',
                'sub' => [
                    //'倉庫管理メンテ' => ['label' => '倉庫管理メンテ'],
                    '倉庫メンテ' => ['label' => getPageTitle('master.soko.index'), 'href' => route('master.soko.index')],
                    //'倉庫品目メンテ' => ['label' => '倉庫品目メンテ'],
                    'm_soko_hinmei' => ['label' => getPageTitle('master.soko_hinmei.index'), 'href' => route('master.soko_hinmei.index')],
                ],
            ],
        ],
    ],
    '汎用検索' => ['label' => getPageTitle($nm = 'hanyou_kensaku.index'), 'href' => route($nm)],
];
