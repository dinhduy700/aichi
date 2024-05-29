<?php
return [
    'base' => [
        'block' => [
            'start' => ['col' => 'A', 'row' => 4],
            'end' => ['col' => 'AP', 'row' => 4],
        ],
        'others' => [
            ['col' => 'F', 'row' => 1, 'value' => date('Y/m/d H:i:s')],
        ]
    ],
    'block' => [
        'A' => ['field' => 'ninusi_cd'],
        'B' => ['field' => 'kana'],
        'C' => ['field' => 'ninusi1_nm'],
        'D' => ['field' => 'ninusi2_nm'],
        'E' => ['field' => 'ninusi_ryaku_nm'],
        'F' => ['field' => 'bumon_cd'],
        'G' => ['field' => 'bumon_nm'],
        'H' => ['field' => 'yubin_no'],
        'I' => ['field' => 'jyusyo1_nm'],
        'J' => ['field' => 'jyusyo2_nm'],
        'K' => ['field' => 'tel'],
        'L' => ['field' => 'fax'],
        'M' => ['field' => 'seikyu_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.seikyu_kbn', [], 1),
                data_get($row, 'seikyu_kbn', ''),
                ''
            );
        }],
        'N' => ['field' => 'seikyu_cd'],
        'O' => ['field' => 'seikyu_nm'],
        'P' => ['field' => 'seikyu_mu_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.seikyu_mu_kbn', [], 1),
                data_get($row, 'seikyu_mu_kbn', ''),
                ''
            );
        }],
        'Q' => ['field' => 'simebi1'],
        'R' => ['field' => 'simebi2'],
        'S' => ['field' => 'simebi3'],
        'T' => ['field' => 'mikakutei_seigyo_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.mikakutei_seigyo_kbn', [], 1),
                data_get($row, 'mikakutei_seigyo_kbn', ''),
                ''
            );
        }],
        'U' => ['field' => 'kin_hasu_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.kin_hasu_kbn', [], 1),
                data_get($row, 'kin_hasu_kbn', ''),
                ''
            );
        }],
        'V' => ['field' => 'kin_hasu_tani'],
        'W' => ['field' => 'zei_keisan_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.zei_keisan_kbn', [], 1),
                data_get($row, 'zei_keisan_kbn', ''),
                ''
            );
        }],
        'X' => ['field' => 'zei_hasu_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.zei_hasu_kbn', [], 1),
                data_get($row, 'zei_hasu_kbn', ''),
                ''
            );
        }],
        'Y' => ['field' => 'zei_hasu_tani'],
        'Z' => ['field' => 'urikake_saki_cd'],
        'AA' => ['field' => 'urikake_saki_nm'],
        'AB' => ['field' => 'nyukin_umu_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.nyukin_umu_kbn', [], 1),
                data_get($row, 'nyukin_umu_kbn', ''),
                ''
            );
        }],
        'AC' => ['field' => 'kaisyu1_dd'],
        'AD' => ['field' => 'kaisyu2_dd'],
        'AE' => ['field' => 'comennt'],
        'AF' => ['field' => 'seikyu_teigi_no'],
        'AG' => ['field' => 'unchin_teigi_no'],
        'AH' => ['field' => 'kensaku_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.kensaku_kbn', [], 1),
                data_get($row, 'kensaku_kbn', ''),
                ''
            );
        }],
        'AI' => ['field' => 'unso_bi_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.unso_bi_kbn', [], 1),
                data_get($row, 'unso_bi_kbn', ''),
                ''
            );
        }],
        'AJ' => ['field' => 'nebiki_ritu'],
        'AK' => ['field' => 'nebiki_hasu_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.nebiki_hasu_kbn', [], 1),
                data_get($row, 'nebiki_hasu_kbn', ''),
                ''
            );
        }],
        'AL' => ['field' => 'nebiki_hasu_tani', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.nebiki_hasu_tani', [], 1),
                data_get($row, 'nebiki_hasu_tani', ''),
                ''
            );
        }],
        'AM' => ['field' => 'mail'],
        'AN' => ['field' => 'okurijyo_hako_kbn', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.okurijyo_hako_kbn', [], 1),
                data_get($row, 'okurijyo_hako_kbn', ''),
                ''
            );
        }],
        'AO' => ['field' => 'biko'],
        'AP' => ['field' => 'kyumin_flg', 'value' => function ($row) {
            return data_get(
                configParam('options.m_ninusi.kyumin_flg', [], 1),
                data_get($row, 'kyumin_flg', ''),
                ''
            );
        }],
    ],

];
