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
        Schema::create('m_hinmei', function (Blueprint $table) {
            $table->comment('品名マスタ');
            $table->integer('hinmei_cd')->primary()->comment('品名CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('hinmei_nm')->nullable()->comment('名称');
            $table->string('hinmei2_cd')->nullable()->comment('品名2CD, 自由入力');
            $table->integer('hinmoku_cd')->nullable()->comment('品目CD, 品目マスタと連携');
            $table->string('tani_cd')->nullable()->comment('単位CD, 名称マスタと連携  kbn=tani');
            $table->decimal('tani_jyuryo', 10, 3)->nullable()->comment('単位重量');
            $table->decimal('haisya_tani_jyuryo', 10, 3)->nullable()->comment('配車単位重量');
            $table->string('syoguti_kbn1')->nullable()->comment('諸口区分１, 1:表示のみ、2:表示後入力');
            $table->string('syoguti_kbn2')->nullable()->comment('諸口区分２, 1:表示のみ、2:表示後入力');
            $table->integer('ninusi_id')->nullable()->comment('荷主CD');
            $table->integer('bumon_cd')->nullable()->comment('部門CD');
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
        Schema::dropIfExists('m_hinmei');
    }
};
