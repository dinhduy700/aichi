<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_ninusi', function (Blueprint $table) {
            $table->comment('荷主マスタ');
            $table->integer('ninusi_cd')->primary()->comment('荷主CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('ninusi1_nm')->nullable()->comment('荷主名1');
            $table->string('ninusi2_nm')->nullable()->comment('荷主名2');
            $table->string('ninusi_ryaku_nm')->nullable()->comment('荷主名略称');
            $table->integer('bumon_cd')->nullable()->comment('担当部門, 部門マスタと連携');
            $table->string('yubin_no')->nullable()->comment('郵便番号');
            $table->string('jyusyo1_nm')->nullable()->comment('住所１');
            $table->string('jyusyo2_nm')->nullable()->comment('住所２');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('fax')->nullable()->comment('FAX番号');
            $table->string('seikyu_kbn')->nullable()->comment('請求区分');
            $table->integer('seikyu_cd')->nullable()->comment('請求先コード');
            $table->string('seikyu_mu_kbn', 1)->nullable()->comment('請求有無区分, 0:請求する、1：請求しない');
            $table->integer('simebi1')->nullable()->comment('締日１');
            $table->integer('simebi2')->nullable()->comment('締日２');
            $table->integer('simebi3')->nullable()->comment('締日３');
            $table->string('mikakutei_seigyo_kbn', 1)->nullable()->comment('未確定制御区分');
            $table->string('kin_hasu_kbn', 1)->nullable()->comment('金額端数区分');
            $table->string('kin_hasu_tani', 1)->nullable()->comment('金額端数単位');
            $table->string('zei_keisan_kbn', 1)->nullable()->comment('消費税計算区分');
            $table->string('zei_hasu_kbn')->nullable()->comment('消費税端数処理区分');
            $table->string('zei_hasu_tani')->nullable()->comment('消費税端数単位');
            $table->integer('urikake_saki_cd')->nullable()->comment('売掛先コード');
            $table->string('nyukin_umu_kbn', 1)->nullable()->comment('入金入力有無');
            $table->integer('kaisyu1_dd')->nullable()->comment('回収日１');
            $table->integer('kaisyu2_dd')->nullable()->comment('回収日２');
            $table->string('comennt')->nullable()->comment('請求書コメント, 自由入力');
            $table->string('seikyu_teigi_no')->nullable()->comment('請求書定義NO');
            $table->string('unchin_teigi_no')->nullable()->comment('運賃確認書定義NO');
            $table->string('kensaku_kbn', 1)->nullable()->comment('検索表示区分');
            $table->string('unso_bi_kbn', 1)->nullable()->comment('運送日区分');
            $table->string('nebiki_ritu')->nullable()->comment('値引き率');
            $table->string('nebiki_hasu_kbn', 1)->nullable()->comment('値引き端数区分');
            $table->string('nebiki_hasu_tani')->nullable()->comment('値引き額端数単位');
            $table->string('mail')->nullable()->comment('メールアドレス');
            $table->string('okurijyo_hako_kbn', 1)->nullable()->comment('送り状発行区分');
            $table->string('biko')->nullable()->comment('備考');

            // 倉庫関連情報
            $table->string('lot_kanri_kbn', 1)->nullable()->comment('ロット管理区分, 0:ロット管理無し、1：ロット１を使用、2：ロット１～２を使用、３：全て使用');
            $table->string('lot1_nm')->nullable()->comment('ロット１名称');
            $table->string('lot2_nm')->nullable()->comment('ロット２名称');
            $table->string('lot3_nm')->nullable()->comment('ロット３名称');
            $table->integer('kisei_kbn')->nullable()->comment('期制区分, 0:対象外、1：１期制、２：２期制、3：３期制');
            $table->integer('ki1_from')->nullable()->comment('1期制from, 1～31');
            $table->integer('ki1_to')->nullable()->comment('1期制to, 1～31');
            $table->integer('ki2_from')->nullable()->comment('2期制from, 1～31');
            $table->integer('ki2_to')->nullable()->comment('2期制to, 1～31');
            $table->integer('ki3_from')->nullable()->comment('3期制from, 1～31');
            $table->integer('ki3_to')->nullable()->comment('3期制to, 1～31');
            $table->string('sekisu_kbn', 1)->nullable()->comment('積数算出方法');
            $table->string('soko_hokan_hasu_kbn', 1)->nullable()->comment('金額端数区分, 0：切捨、1：切上、2：四捨五入');
            $table->string('soko_hokan_hasu_tani', 1)->nullable()->comment('金額端数単位, 0：１円、1：10円、2：100円、3：1000円');
            $table->string('hokanryo_meisyo')->nullable()->comment('保管料請求書名称');
            $table->string('nieki_sansyutu_kbn', 1)->nullable()->comment('荷役料算出区分, 0：計算しない、１：入出庫別々、２：入出庫同一');
            $table->string('nieki_hokan_hasu_kbn', 1)->nullable()->comment('荷役端数区分, 0：切捨、1：切上、2：四捨五入');
            $table->string('nieki_hokan_hasu_tani', 1)->nullable()->comment('荷役端数単位, 0：１円、1：10円、2：100円、3：1000円');
            $table->string('nieki_nyuko_nm')->nullable()->comment('荷役料請求書名称　入庫');
            $table->string('nieki_syuko_nm')->nullable()->comment('荷役料請求書名称　出庫');
            $table->string('nieki_nieki_nm')->nullable()->comment('荷役料請求書名称　荷役');
            $table->integer('soko_seikyu_cd')->nullable()->comment('倉庫請求先コード, 荷主マスタの荷主CDと連携');
            $table->integer('soko_bumon_cd')->nullable()->comment('倉庫売上部門コード, 部門マスタと連携');
            $table->decimal('nyuko_tanka', 10, 2)->nullable()->comment('荷役単価（入庫）');
            $table->decimal('syuko_tanka', 10, 2)->nullable()->comment('荷役単価（出庫）');
            $table->decimal('hokan_tanka', 10, 2)->nullable()->comment('保管料単価');

            $table->string('kyumin_flg', 1)->nullable()->comment('休眠フラグ, 0：通常、１：休眠とし、検索画面で非表示');

            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_ninusi');
    }
};
