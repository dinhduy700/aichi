<?php
$constants = [
    'PAGE_SIZE' => 50,
    'TAX_RATE' => 0.1,
    'USER_PASSWD_EDIT' => 'qiIs0D*3qq!PFpp3',

    'BUMON_CD_HONTEN' => 10,
    'BUMON_CD_HOKURIKU_YUGI' => 11,
    'BUMON_CD_HOKURIKU' => 21,
    'BUMON_CD_KANTO' => 22,

    //https://www.postgresql.org/docs/14/datatype-numeric.html
    'DB_INT_SIGNED_MIN' => -2147483648,
    'DB_INT_SIGNED_MAX' => 2147483647,


    'HASU_KBN' => [
        '0' => '切捨',
        '1' => '切上',
        '2' => '四捨五入',
    ],
    'HASU_TANI' => [
        '0' => '1円',
        '1' => '10円',
        '2' => '100円',
        '3' => '1000円',
    ],
    'KYUMIN_FLG' => [
        '0' => '通常',
        '1' => '休眠',
    ],
    'SEIKYU_MU_KBN' => [
        '0' => '請求する',
        '1' => '請求しない',
    ],
    'MIKAKUTEI_SEIGYO_KBN' => [
        '0' => '未確定印字無',
        '1' => '確定分で印字',
    ],
    'ZEI_KEISAN_KBN' => [
        '0' => '計算無し',
        '1' => '内税',
        '2' => '請求一括外税',
        '3' => '明細毎外税',
    ],

    // 名称区分: tani（単位）、syubetu（種別）、syasyu（車種）、gyosya（業者）、rikuun(陸運支局)、jyutyu（受注区分）
    'MEISYO_KBN_TANI' => 'tani',
    'MEISYO_KBN_SYUBETU' => 'syubetu',
    'MEISYO_KBN_SYASYU' => 'syasyu',
    'MEISYO_KBN_GYOSYA' => 'gyosya',
    'MEISYO_KBN_RIKUUN' => 'rikuun',
    'MEISYO_KBN_JYUTYU' => 'jyutyu',
    'MEISYO_KBN_GENKIN' => 'genkin',
    'MEISYO_KBN_TANKA' => 'tanka',
    'MEISYO_KBN_UNCHINKAKUTEI' => 'unchinkakutei',

    'SEIKYU_KAKUTEI_FLG_ALL_1' => '確定',

    'MENZEI_KBN' => [
        '0' => '課税',
        '1' => '免税'
    ],
    'YOSYA_KIN_MIKAKUTEI_KBN' => [
        '0' => '確定',
        '1' => '未確定'
    ],
    'SOURYO_KBN' => [
        '0' => '無し',
        '1' => '有り'
    ],
    'NYUSYUKO_KBN' => [
        '1' => '入庫',
        '2' => '出庫'
    ],
    'NYUSYUKO_KBN_SUPPORT' => [
        '1' => '入庫',
        '2' => '出庫',
        '4' => '棚卸',
        '5' => '在庫移動'
    ],

    'LIMIT_SUGGEST' => null,

    'HANYOU_KENSAKU' => [
        'kinou' => [
            'yosya_geppo' => [
                'title' => '庸車月報',
                'options' => [
                    'ninusi_cd' => '荷主',
                    'yousya_cd' => '庸車先',
                    'nipou_dt' => '日報日付'
                ]
            ], // yosya_geppo
            'suitocho_nyuryoku_list' => [
                'title' => '出納帳入力用リスト',
                'options' => [
                    'kaisyu_dt' => '受領日',
                    'jyutyu_kbn' => '受注区分'
                ]
            ], //suitocho_nyuryoku_list
            'nichibetsu_uriage_kingaku' => [
                'title' => '日別売上金額',
                'options' => [
                    'ninusi_cd' => '荷主',
                    'haitatu_dt' => '配達日'
                ]
            ], //nichibetsu_uriage_kingaku
            'mihikiate_nyukin_denpyo' => [
                'title' => '未引当の入金伝票',
                'options' => [
                    // no-option
                ]
            ], //mihikiate_nyukin_denpyo
            'mikakutei_unchin_list' => [
                'title' => '未確定運賃リスト',
                'options' => [
                    'bumon_cd' => '部門',
                    'ninusi_cd' => '荷主',
                    'seikyu_sime_dt' => '請求締日',
                    'add_tanto_cd' => '入力担当'
                ]
            ], //mikakutei_unchin_list
            'genkin_kbn_checklist' => [
                'title' => '現金区分チェックリスト',
                'options' => [
                    'bumon_cd' => '部門',
                    'haitatu_dt' => '配達日',
                ]
            ],//genkin_kbn_checklist
            'genkin_kaishu_checklist' => [
                'title' => '現金回収チェックリスト',
                'options' => [
                    'haitatu_dt' => '配達日',
                ]
            ], //genkin_kaishu_checklist
            'idoharigami' => [
                'title' => '移動張紙',
                'options' => [
                    'haitatu_dt' => '配達日',
                    'hinmoku_cd' => 'メーカー',
                    'hinmei_cd' => '機種'
                ]
            ], //idoharigami
            'nohin_meisai' => [
                'title' => '納品明細',
                'options' => [
                    'haitatu_dt' => '配達日',
                    'hachaku_cd' => '着地コード'
                ]
            ], //nohin_meisai
            'keiri_soft_renkei' => [
                'title' => '経理ソフト連携',
                'options' => [
                    'unso_dt' => '運送日',
                ]
            ], //keiri_soft_renkei
            'ninusi_list' => [
                'title' => '荷主一覧',
                'options' => [
                    'ninusi_cd' => '荷主',
                    'nipou_dt' => '日報日付',
                    'jyomuin_cd' => '運転者',
                ]
            ], //ninusi_list
            'seikyu_meisai' => [
                'title' => '請求明細',
                'options' => [
                    'haitatu_dt' => '配達日',
                    'jyomuin_cd' => '運転者',
                ]
            ], //seikyu_meisai
            'seikyuzan_kakunin' => [
                'title' => '請求残高確認',
                'options' => [
                    'ninusi_cd' => '荷主',
                    'seikyu_sime_dt' => '請求締日',
                    'konkai_torihiki_kin_flg' => '消込区分（0:全件,1:未消込のみ）'
                ]
            ], //seikyuzan_kakunin
            'yugidai_ninusicd_search' => [
                'title' => '遊技台荷主コード検索'
            ], //yugidai_ninusicd_search
            'unten_geppo' => [
                'title' => '運転月報',
                'options' => [
                    'ninusi_cd' => '荷主',
                    'nipou_dt' => '日報日付',
                    'jyomuin_cd' => '運転者',
                ]
            ], //unten_geppo
            'ryoshusho_honten' => [
                'title' => '領収書（本社）',
                'options' => [
                    'bumon_cd' => '部門',
                    'ninusi_cd' => '荷主',
                    'haitatu_dt' => '配達日',
                    'syaban' => '車番',
                ],
                'labels' => [
                    'kaisya_mei' => '有限会社　愛知高速運輸',//会社名
                    'jyusyo' => '愛知県半田市潮干町2-3',//住所
                    'tel' => 'TEL:0569-20-1601',//電話番号
                ],
            ], //ryoshusho_honten
            'ryoshusho_hokuriku' => [
                'title' => '領収書（北陸）',
                'options' => [
                    'bumon_cd' => '部門',
                    'ninusi_cd' => '荷主',
                    'haitatu_dt' => '配達日',
                    'syaban' => '車番',
                ],
                'labels' => [
                    'kaisya_mei' => '有限会社　愛知高速運輸',//会社名
                    'jyusyo' => '石川県金沢市森戸1丁目161番地',//住所
                    'tel' => 'TEL:(076)240-2912',//電話番号
                ],
            ], //ryoshusho_hokuriku
            'ryoshusho_kanto' => [
                'title' => '領収書（関東）',
                'options' => [
                    'bumon_cd' => '部門',
                    'ninusi_cd' => '荷主',
                    'haitatu_dt' => '配達日',
                    'syaban' => '車番',
                ],
                'labels' => [
                    'kaisya_mei' => '有限会社　愛知高速運輸',//会社名
                    'jyusyo' => "千葉県柏市松ヶ崎新田１３－１　ロジポート北柏オフィス３Ｆ－Ａ",//住所
                    'tel' => 'TEL:04-7197-7885',//電話番号
                ],
            ] //ryoshusho_kanto
        ],
        'field' => [
            'bumon_cd' => '部門',
            'ninusi_cd' => '荷主',
            'yousya_cd' => '庸車先',
            'syuka_dt' => '集荷日',
            'haitatu_dt' => '配達日',
            'unso_dt' => '運送日',
            'nipou_dt' => '日報日付',
            'kaisyu_dt' => '受領日',
            'seikyu_sime_dt' => '請求締日',
            'jyutyu_kbn' => '受注区分',
            'hachaku_cd' => '着地コード',
            'hinmoku_cd' => 'メーカー',
            'hinmei_cd' => '機種',
            'gyosya_cd' => '業者',
            'add_tanto_cd' => '入力担当',
            'syaban' => '車番',
            'jyomuin_cd' => '運転者',
            'konkai_torihiki_kin_flg' => '消込区分（0:全件,1:未消込のみ）'
        ],
        'operator' => [
            '=' => '＝',
            '>=' => '＞＝',
            '<=' => '＜＝',
            '>' => '＞',
            '<' => '＜',
            '<>' => '＜＞'
        ],
        'logical_operator' => [
            'and' => 'AND',
            'or' => 'OR'
        ]
    ]
];
return array_merge($constants, [
    'options' => [
        //05.売上データ
        't_uriage' => [
            //現金CD
            'genkin_cd' => [
                '1' => '現金',
            ],
            //運賃未確定区分
            'unchin_mikakutei_kbn' => [
                '0' => '確定',
                '1' => '未確定',
                '9' => '請求なし',
            ],
        ],
        't_seikyu' => [
            'seikyu_hako_flg' => ['0' => '未発行', '1' => '発行済'],
            'seikyu_kakutei_flg' => ['0' => '未確定', '1' => '確定済'],
        ],
        //入出庫ヘッダデータ
        't_nyusyuko_head' => [
            'nyusyuko_kbn' => [//入出庫区分
                '1' => '入庫',
                '2' => '出庫',
                '3' => 'スルー',
                '4' => '棚卸',
                '5' => '在庫移動',
                '6' => '名義変更',
            ],
        ],
        //102.荷主マスタ
        'm_ninusi' => [
            //請求区分
            'seikyu_kbn' => [
                '0' => '個別請求',
                '1' => '本社一括',
                '2' => '支店請求本社入金',
            ],
            //請求有無区分
            'seikyu_mu_kbn' => $constants['SEIKYU_MU_KBN'],
            //未確定制御区分
            'mikakutei_seigyo_kbn' => $constants['MIKAKUTEI_SEIGYO_KBN'],
            //金額端数区分
            'kin_hasu_kbn' => $constants['HASU_KBN'],
            //金額端数単位
            'kin_hasu_tani' => $constants['HASU_TANI'],
            //消費税計算区分
            'zei_keisan_kbn' => $constants['ZEI_KEISAN_KBN'],
            //消費税端数処理区分
            'zei_hasu_kbn' => $constants['HASU_KBN'],
            //消費税端数単位
            'zei_hasu_tani' => $constants['HASU_TANI'],
            //入金入力有無
            'nyukin_umu_kbn' => [
                '0' => '入金入力する',
                '1' => '入金入力しない',
            ],
            //請求書定義NO
            'seikyu_teigi_no' => [
                '1' => '請求書（遊技台）',
                '2' => '請求書（一般）',
            ],
            //検索表示区分
            'kensaku_kbn' => [
                '0' => '荷主検索に表示する',
                '1' => '荷主検索に表示しない',
            ],
            //運送日区分
            'unso_bi_kbn' => [
                '0' => '集荷日',
                '1' => '配達日',
            ],
            //値引き端数区分
            'nebiki_hasu_kbn' => $constants['HASU_KBN'],
            //値引き額端数単位
            'nebiki_hasu_tani' => $constants['HASU_TANI'],
            //送り状発行区分
            'okurijyo_hako_kbn' => [
                '0' => '必要',
                '9' => '不要',
            ],
            //休眠フラグ
            'kyumin_flg' => $constants['KYUMIN_FLG'],
            'lot_kanri_kbn' => [
                '0' => 'ロット管理無し',
                '1' => 'ロット１を使用',
                '2' => 'ロット１～２を使用',
                '3' => '全て使用',
            ],
            'kisei_kbn' => [
                '0' => '対象外',
                '1' => '１期制',
                '2' => '２期制',
                '3' => '３期制',
            ],
            'sekisu_kbn' => [
                '0' => '計算しない',
                '1' => '1期残+2期残+3期残',
                '2' => '前残+１期残+２期残',
                '3' => '？期入庫+？期残',
                '4' => '前期残+？期入庫',
                '5' => '？期入庫+？期残（3期除く）',
                '6' => '？期入庫+？期残（1期除く）',
            ],
            'soko_hokan_hasu_kbn' => $constants['HASU_KBN'],
            'soko_hokan_hasu_tani' => $constants['HASU_TANI'],

            'nieki_sansyutu_kbn' => [
                '0' => '計算しない',
                '1' => '入出庫別々',
                '2' => '入出庫同一'
            ],
            'nieki_hokan_hasu_kbn' => $constants['HASU_KBN'],
            'nieki_hokan_hasu_tani' => $constants['HASU_TANI'],

        ],
        //110.名称マスタ
        'm_meisyo' => [
            //名称区分
            'meisyo_kbn' => [
                $constants['MEISYO_KBN_TANI'] => '単位（tani）',
                $constants['MEISYO_KBN_SYUBETU'] => '種別（syubetu）',
                $constants['MEISYO_KBN_SYASYU'] => '車種（syasyu）',
                $constants['MEISYO_KBN_GYOSYA'] => '業者（gyosya）',
                $constants['MEISYO_KBN_RIKUUN'] => '陸運支局(rikuun)',
                $constants['MEISYO_KBN_JYUTYU'] => '受注区分（jyutyu）',
                $constants['MEISYO_KBN_GENKIN'] => '現金CD（genkin）',
                $constants['MEISYO_KBN_TANKA'] => '単価区分（tanka）',
                $constants['MEISYO_KBN_UNCHINKAKUTEI'] => '運賃確定区分（unchinkakutei）',
            ],
            //休眠フラグ
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_jyomuin' => [
            //休眠フラグ
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_bumon' => [
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_hinmoku' => [
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_hinmei' => [
            'kyumin_flg' => $constants['KYUMIN_FLG'],
            'syoguti_kbn1' => [
                '1' => '表示のみ',
                '2' => '表示後入力'
            ],
            'syoguti_kbn2' => [
                '1' => '表示のみ',
                '2' => '表示後入力'
            ]
        ],
        'm_syaryo' => [
            'jiyo_kbn' => [
                '0' => '自社',
                '1' => '庸車'
            ],
            'sekisai_kbn' => [
                '0' => '5ｔまで',
                '1' => '5ｔ以上'
            ],
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_yousya' => [
            'siharai_kbn' => [
                '0' => '個別支払い',
                '1' => '本社一括支払',
                '2' => '支店仕入本社支払',
            ],
            'seikyu_mu_kbn' => $constants['SEIKYU_MU_KBN'],
            'mikakutei_seigyo_kbn' => $constants['MIKAKUTEI_SEIGYO_KBN'],
            'kin_hasu_kbn' => $constants['HASU_KBN'],
            'kin_hasu_tani' => $constants['HASU_TANI'],
            'zei_keisan_kbn' => $constants['ZEI_KEISAN_KBN'],
            'zei_hasu_kbn' => $constants['HASU_KBN'],
            'zei_hasu_tani' => $constants['HASU_TANI'],
            'siharai_umu_kbn' => [
                '0' => '支払する',
                '1' => '支払しない'
            ],
            'siharai_nyuryoku_umu_kbn' => [
                '0' => '支払入力する',
                '1' => '支払入力しない'
            ],
            'kensaku_kbn' => [
                '0' => '検索表示あり',
                '1' => '検索表示なし'
            ],
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_hachaku' => [
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_biko' => [
            'syubetu_kbn' => [
                '0' => '運送明細',
                '1' => '入金/支払い',
                '2' => '経費',
            ],
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_user' => [
            'group' => [
                '1' => '一般',
                '2' => '管理者'
            ],
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_soko' => [
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
        'm_soko_hinmei' => [
            'ondo' => [
                '0' => '無し',
                '1' => '常温',
                '2' => '冷蔵',
                '3' => '冷凍',
            ],
            'zaiko_kbn' => [
                '0' => '在庫品',
                '1' => 'スルー品',
            ],
            'keisan_kb' => [
                '0' => '数量*単価',
                '1' => '重量*単価',
                '2' => '重量/1000*単価',
                '3' => 'ケース*単価',
            ],
            'seikyu_keta' => [
                '0' => '小数点無し',
                '1' => '小数点１桁',
                '2' => '小数点２桁',
                '3' => '小数点３桁',
            ],
            'kyumin_flg' => $constants['KYUMIN_FLG'],
        ],
    ],
]);
