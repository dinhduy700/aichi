<?php
return [
    'm_user' => [
        'user_cd' => 'ユーザID',
        'passwd' => 'パスワード',
        'group' => '権限グループ',
        'biko' => '備考',
        'kyumin_flg' => '休眠フラグ',
        'add_user_cd' => '登録者',
        'add_dt' => '登録日',
        'upd_user_cd' => '更新者',
        'upd_dt' => '更新日',
    ],
    'm_meisyo' => [
        'meisyo_kbn' => '名称区分',
        'meisyo_cd' => '名称コード',
        'kana' => '読みカナ',
        'meisyo_nm' => '名称',
        'jyuryo_kansan' => '重量換算係',
        'sekisai_kbn' => '積載区分',
        'kyumin_flg' => '休眠フラグ'
    ],
    'm_ninusi' => [
        'ninusi_cd' => '荷主コード',
        'kana' => 'ヨミガナ',
        'ninusi1_nm' => '荷主名1',
        'ninusi2_nm' => '荷主名2',
        'ninusi_ryaku_nm' => '荷主名略称',
        'bumon_cd' => '担当部門コード',
        'bumon_nm' => '担当部門名',
        'yubin_no' => '郵便番号',
        'jyusyo1_nm' => '住所１',
        'jyusyo2_nm' => '住所２',
        'tel' => '電話番号',
        'fax' => 'FAX',
        'seikyu_kbn' => '請求区分',
        'seikyu_cd' => '請求先コード',
        'seikyu_nm' => '請求先名',
        'seikyu_mu_kbn' => '請求有無区分',
        'simebi1' => '締日１',
        'simebi2' => '締日２',
        'simebi3' => '締日３',
        'mikakutei_seigyo_kbn' => '未確定制御区分',
        'kin_hasu_kbn' => '金額端数区分',
        'kin_hasu_tani' => '金額端数単位',
        'zei_keisan_kbn' => '消費税計算区分',
        'zei_hasu_kbn' => '消費税端数処理区分',
        'zei_hasu_tani' => '消費税端数単位',
        'urikake_saki_cd' => '売掛先コード',
        'urikake_saki_nm' => '売掛先名',
        'nyukin_umu_kbn' => '入金入力有無',
        'kaisyu1_dd' => '回収日１',
        'kaisyu2_dd' => '回収日２',
        'comennt' => '請求書コメント',
        'seikyu_teigi_no' => '請求書定義NO',
        'unchin_teigi_no' => '運賃確認書定義NO',
        'kensaku_kbn' => '検索表示区分',
        'unso_bi_kbn' => '運送日区分',
        'nebiki_ritu' => '値引き率',
        'nebiki_hasu_kbn' => '値引き端数区分',
        'nebiki_hasu_tani' => '値引き額端数単位',
        'mail' => 'メールアドレス',
        'okurijyo_hako_kbn' => '送り状発行区分',
        'biko' => '備考',
        'kyumin_flg' => '休眠フラグ',

        //倉庫関連情報
        'lot_kanri_kbn' => 'ロット管理区分',
        'lot1_nm' => 'ロット１名称',
        'lot2_nm' => 'ロット２名称',
        'lot3_nm' => 'ロット３名称',
        'kisei_kbn' => '期制区分',
        'ki1_from' => '1期制from',
        'ki1_to' => '1期制to',
        'ki2_from' => '2期制from',
        'ki2_to' => '2期制to',
        'ki3_from' => '3期制from',
        'ki3_to' => '3期制to',
        'sekisu_kbn' => '積数算出方法',
        'soko_hokan_hasu_kbn' => '金額端数区分',
        'soko_hokan_hasu_tani' => '金額端数単位',
        'hokanryo_meisyo' => '保管料請求書名称',
        'nieki_sansyutu_kbn' => '荷役料算出区分',
        'nieki_hokan_hasu_kbn' => '荷役端数区分',
        'nieki_hokan_hasu_tani' => '荷役端数単位',
        'nieki_nyuko_nm' => '荷役料請求書名称　入庫',
        'nieki_syuko_nm' => '荷役料請求書名称　出庫',
        'nieki_nieki_nm' => '荷役料請求書名称　荷役',
        'soko_seikyu_cd' => '倉庫請求先コード',
        'soko_bumon_cd' => '倉庫売上部門コード',
        'nyuko_tanka' => '荷役単価（入庫）',
        'syuko_tanka' => '荷役単価（出庫）',
        'hokan_tanka' => '保管料単価',

    ],
    'm_hachaku' => [
        'hachaku_cd'            => '発着地コード',
        'kana'                  => 'ヨミガナ',
        'hachaku_nm'            => '名称',
        'atena_ninusi_id'       => '宛名荷主コード', // table
        'atena_ninusi_id_form'  => '宛名荷主', // form
        'atena'                 => '宛名',
        'atena_ninusi_nm'       => '宛名荷主名',
        'jyusyo1_nm'            => '住所１',
        'jyusyo2_nm'            => '住所２',
        'tel'                   => '電話番号',
        'fax'                   => 'FAX番号',
        'ninusi_id'             => '荷主コード',
        'ninusi_nm'             => '荷主名',
        'kyumin_flg'            => '休眠フラグ',
    ],
    'm_jyomuin' => [
        'jyomuin_cd' => '乗務員コード',
        'kana' => 'ヨミガナ',
        'jyomuin_nm' => '名称',
        'bumon_cd' => '所属部門コード',
        'kyumin_flg' => '休眠フラグ',
        'mobile_tel' => '携帯番号',
        'mail' => 'メールアドレス',
        'add_user_cd' => '登録者',
        'add_dt' => '登録日',
        'upd_user_cd' => '更新者',
        'upd_dt' => '更新日',
    ],
    'm_bumon' => [
        'bumon_cd' => '部門コード',
        'kana' => '読みカナ',
        'bumon_nm' => '名称',
        'kyumin_flg' => '休眠フラグ'
    ],
    'm_hinmoku' => [
        'hinmoku_cd' => '品目コード',
        'kana' => 'ヨミガナ',
        'hinmoku_nm' => '名称',
        'kyumin_flg' => '休眠フラグ'
    ],
    'm_hinmei' => [
        'hinmei_cd' => '品名コード',
        'kana' => '読みカナ',
        'hinmei_nm' => '名称',
        'hinmei2_cd' => '品名２コード',
        'hinmoku_cd' => '品目コード',
        'tani_cd' => '単位コード',
        'tani_jyuryo' => '単位重量',
        'haisya_tani_jyuryo' => '配車単位重量',
        'syoguti_kbn1' => '諸口区分１',
        'syoguti_kbn2' => '諸口区分２',
        'ninusi_id' => '荷主コード',
        'bumon_cd' => '部門コード',
        'kyumin_flg' => '休眠フラグ',
    ],
    'm_syaryo' => [
        'syaryo_cd' => '車両コード',
        'syasyu_cd' => '車種コード',
        'jiyo_kbn' => '自庸区分',
        'jyomuin_cd' => '乗務員コード',
        'yousya_cd' => '庸車コード',
        'bumon_cd' => '部門コード',
        'sekisai_kbn' => '積載区分',
        'sekisai_jyuryo' => '積載重量',
        'point' => 'ポイント',
        'himoku_ritu' => '費目計算用率',
        'haisya_dt' => '廃車日付',
        'rikuun_cd' => '陸運支局コード',
        'car_number_syubetu' => '種別',
        'car_number_kana' => 'かな',
        'car_number' => 'ナンバー',
        'kyumin_flg' => '休眠フラグ',
    ],
    'm_yousya' => [
        'yousya_cd'                 => '庸車コード',
        'kana'                      => 'ヨミガナ',
        'yousya1_nm'                => '庸車名1',
        'yousya2_nm'                => '庸車名2',
        'yousya_ryaku_nm'           => '庸車名略称',
        'bumon_cd'                  => '部門CD',
        'bumon_cd_form'             => '担当部門',
        'bumon_nm'                  => '部門名',
        'yubin_no'                  => '郵便番号',
        'jyusyo1_nm'                => '住所１',
        'jyusyo2_nm'                => '住所２',
        'tel'                       => '電話番号',
        'fax'                       => 'FAX番号',
        'siharai_kbn'               => '支払区分',
        'siharai_cd'                => '支払先コード',
        'siharai_nm'                => '支払先名',
        'yousya_ritu'               => '庸車料率',
        'siharai_umu_kbn'           => '支払有無区分',
        'simebi1'                   => '締日１',
        'simebi2'                   => '締日２',
        'simebi3'                   => '締日３',
        'mikakutei_seigyo_kbn'      => '未確定制御区分',
        'kin_hasu_kbn'              => '金額端数区分',
        'kin_hasu_tani'             => '金額端数単位',
        'zei_keisan_kbn'            => '消費税計算区分',
        'zei_hasu_kbn'              => '消費税端数処理区分',
        'zei_hasu_tani'             => '消費税端数単位',
        'kaikake_saki_cd'           => '買掛先コード',
        'kaikake_saki_nm'           => '買掛先名',
        'siharai_nyuryoku_umu_kbn'  => '支払入力有無',
        'siharai1_dd'               => '支払日１',
        'siharai2_dd'               => '支払日２',
        'comennt'                   => '請求書コメント',
        'kensaku_kbn'               => '検索表示区分',
        'mail'                      => 'メールアドレス',
        'haisya_biko'               => '配車備考',
        'biko'                      => '備考',
        'kyumin_flg'                => '休眠フラグ',
        'add_user_cd'               => '登録者',
        'add_dt'                    => '登録日',
        'upd_user_cd'               => '更新者',
        'upd_dt'                    => '更新日',
    ],
    'm_biko' => [
        'biko_cd'       => '備考コード',
        'kana'          => 'ヨミガナ',
        'biko_nm'       => '名称',
        'syubetu_kbn'   => '備考種別',
        'kyumin_flg'    => '休眠フラグ',
    ],
    'm_user' => [
        'user_cd' => 'ユーザID',
        'passwd' => 'パスワード',
        'group' => '権限グループ',
        'biko' => '備考',
        'kyumin_flg' => '休眠フラグ',
    ],
    'm_soko' => [
        'bumon_cd' => '部門コード',
        'soko_cd' => '倉庫コード',
        'kana' => 'ヨミガナ',
        'soko_nm' => '名称',
        'kyumin_flg' => '休眠フラグ',
        'add_user_cd' => '登録者',
        'add_dt' => '登録日',
        'upd_user_cd' => '更新者',
        'upd_dt' => '更新日',
    ],
    'm_soko_hinmei' => [
        'ninusi_cd'         => '荷主コード',
        'ninusi_nm'         => '荷主名称',
        'hinmei_cd'         => '品名コード',
        'kana'              => 'ヨミガナ',
        'hinmei_nm'         => '品名名称',
        'kikaku'            => '商品規格',
        'ondo'              => '温度帯',
        'zaiko_kbn'         => '在庫区分',
        'case_cd'           => 'ケース単位コード',
        'case_nm'           => 'ケース単位名称',
        'irisu'             => '入り数',
        'hasu_kiriage'      => '端数切り上げ数',
        'bara_tani'         => 'バラ単位コード',
        'bara_tani_nm'      => 'バラ単位名称',
        'bara_tani_juryo'   => 'バラ重量',
        'uke_tanka'         => '受寄物単価',
        'seikyu_hinmei_cd'  => '請求品名コード',
        'seikyu_hinmei_nm'  => '請求品名名称',
        'keisan_kb'         => '請求額計算区分',
        'seikyu_keta'       => '請求書印字少数桁',
        'seikyu_bunbo'      => '請求書印字分母',
        'nieki_nyuko_tanka' => '荷役単価（入庫）',
        'nieki_syuko_tanka' => '荷役単価（出庫）',
        'hokanryo_kin'      => '保管料単価',
        'bumon_cd'          => '部門コード',
        'bumon_nm'          => '部門名称',
        'kyumin_flg'        => '休眠フラグ',
  ],
];