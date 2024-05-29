<?php 
return [
    [
        'field' => 'ninusi_cd',
        'title' => '荷主コード',
        'formatter' => 'formatNinusiCd',
    ],
    [
        'field' => 'kana',
        'title' => 'ヨミガナ',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'ninusi1_nm',
        'title' => '荷主名1',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'ninusi2_nm',
        'title' => '荷主名2',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'ninusi_ryaku_nm',
        'title' => '荷主名略称',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'bumon_cd',
        'title' => '部門コード',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'bumon_nm',
        'title' => '部門名',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'yubin_no',
        'title' => '郵便番号',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'jyusyo1_nm',
        'title' => '住所１',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'jyusyo2_nm',
        'title' => '住所２',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'tel',
        'title' => '電話番号',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'fax',
        'title' => 'FAX番号',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'seikyu_kbn',
        'title' => '請求区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.seikyu_kbn', [], 1)
    ],
    [
        'field' => 'seikyu_cd',
        'title' => '請求先コード',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'seikyu_nm',
        'title' => '請求先名',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'seikyu_mu_kbn',
        'title' => '請求有無区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.seikyu_mu_kbn', [], 1)
    ],
    [
        'field' => 'simebi1',
        'title' => '締日１',
        'formatter' => 'formatNinusi',
        'class' => 'text-right'
    ],
    [
        'field' => 'simebi2',
        'title' => '締日２',
        'formatter' => 'formatNinusi',
        'class' => 'text-right'
    ],
    [
        'field' => 'simebi3',
        'title' => '締日３',
        'formatter' => 'formatNinusi',
        'class' => 'text-right'
    ],
    [
        'field' => 'mikakutei_seigyo_kbn',
        'title' => '未確定制御区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.mikakutei_seigyo_kbn', [], 1)
    ],
    [
        'field' => 'kin_hasu_kbn',
        'title' => '金額端数区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.kin_hasu_kbn', [], 1)
    ],
    [
        'field' => 'kin_hasu_tani',
        'title' => '金額端数単位',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.kin_hasu_tani', [], 1)
    ],
    [
        'field' => 'zei_keisan_kbn',
        'title' => '消費税計算区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.zei_keisan_kbn', [], 1)
    ],
    [
        'field' => 'zei_hasu_kbn',
        'title' => '消費税端数処理区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.zei_hasu_kbn', [], 1)
    ],
    [
        'field' => 'zei_hasu_tani',
        'title' => '消費税端数単位',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.zei_hasu_tani', [], 1)
    ],
    [
        'field' => 'urikake_saki_cd',
        'title' => '売掛先コード',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'urikake_saki_nm',
        'title' => '売掛先名',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'nyukin_umu_kbn',
        'title' => '入金入力有無',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.nyukin_umu_kbn', [], 1)
    ],
    [
        'field' => 'kaisyu1_dd',
        'title' => '回収日１',
        'formatter' => 'formatNinusi',
        'class' => 'text-right'
    ],
    [
        'field' => 'kaisyu2_dd',
        'title' => '回収日２',
        'formatter' => 'formatNinusi',
        'class' => 'text-right'
    ],
    [
        'field' => 'comennt',
        'title' => '請求書コメント',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'seikyu_teigi_no',
        'title' => '請求書定義NO',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.seikyu_teigi_no', [], 1)
    ],
    [
        'field' => 'unchin_teigi_no',
        'title' => '運賃確認書定義NO',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'kensaku_kbn',
        'title' => '検索表示区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.kensaku_kbn', [], 1)
    ],
    [
        'field' => 'unso_bi_kbn',
        'title' => '運送日区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.unso_bi_kbn', [], 1)
    ],
    [
        'field' => 'nebiki_ritu',
        'title' => '値引き率',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'nebiki_hasu_kbn',
        'title' => '値引き端数区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.nebiki_hasu_kbn', [], 1)
    ],
    [
        'field' => 'nebiki_hasu_tani',
        'title' => '値引き額端数単位',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.nebiki_hasu_tani', [], 1)
    ],
    [
        'field' => 'mail',
        'title' => 'メールアドレス',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'okurijyo_hako_kbn',
        'title' => '送り状発行区分',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.okurijyo_hako_kbn', [], 1)
    ],
    [
        'field' => 'biko',
        'title' => '備考',
        'formatter' => 'formatNinusi',
    ],
    [
        'field' => 'kyumin_flg',
        'title' => '休眠フラグ',
        'formatter' => 'formatNinusiKbn',
        'options' => configParam('options.m_ninusi.kyumin_flg', [], 1)
    ],
];