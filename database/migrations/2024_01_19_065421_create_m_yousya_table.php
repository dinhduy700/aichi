<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_yousya', function (Blueprint $table) {
            $table->comment('庸車先マスタ');
            $table->integer('yousya_cd')->primary()->comment('庸車CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('yousya1_nm')->nullable()->comment('庸車名1');
            $table->string('yousya2_nm')->nullable()->comment('庸車名2');
            $table->string('yousya_ryaku_nm')->nullable()->comment('庸車名略明');
            $table->integer('bumon_cd')->nullable()->comment('担当部門');
            $table->string('yubin_no')->nullable()->comment('郵便番号');
            $table->string('jyusyo1_nm')->nullable()->comment('住所１');
            $table->string('jyusyo2_nm')->nullable()->comment('住所２');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('fax')->nullable()->comment('FAX番号');
            $table->string('siharai_kbn')->nullable()->comment('支払区分、0：個別支払い、1：本社一括支払、2：支店仕入本社支払');
            $table->integer('siharai_cd')->nullable()->comment('支払先コード');
            $table->decimal('yousya_ritu', 10, 1)->nullable()->comment('庸車料率');
            $table->string('siharai_umu_kbn', 1)->nullable()->comment('支払有無区分、0:支払する、1：支払しない');
            $table->integer('simebi1')->nullable()->comment('締日１');
            $table->integer('simebi2')->nullable()->comment('締日２');
            $table->integer('simebi3')->nullable()->comment('締日３');
            $table->string('mikakutei_seigyo_kbn', 1)->nullable()->comment('未確定制御区分');
            $table->string('kin_hasu_kbn', 1)->nullable()->comment('金額端数区分');
            $table->string('kin_hasu_tani', 1)->nullable()->comment('金額端数単位');
            $table->string('zei_keisan_kbn', 1)->nullable()->comment('消費税計算区分');
            $table->string('zei_hasu_kbn')->nullable()->comment('消費税端数処理区分');
            $table->string('zei_hasu_tani')->nullable()->comment('消費税端数単位');
            $table->integer('kaikake_saki_cd')->nullable()->comment('買掛先コード');
            $table->string('siharai_nyuryoku_umu_kbn', 1)->nullable()->comment('支払入力有無、0:支払入力する、1:支払入力しない');
            $table->integer('siharai1_dd')->nullable()->comment('支払日１');
            $table->integer('siharai2_dd')->nullable()->comment('支払日２');
            $table->string('comennt')->nullable()->comment('請求書コメント');
            $table->string('kensaku_kbn', 1)->nullable()->comment('検索表示区分');
            $table->string('mail')->nullable()->comment('メールアドレス');
            $table->string('haisya_biko')->nullable()->comment('配車備考');
            $table->string('biko')->nullable()->comment('備考');
            $table->string('kyumin_flg', 1)->nullable()->comment('休眠フラグ, 0：通常、１：休眠とし、検索画面で非表示');
            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_yousya');
    }
};
