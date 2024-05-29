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
        Schema::create('m_jyomuin', function (Blueprint $table) {
            $table->comment('乗務員マスタ');
            $table->integer('jyomuin_cd')->primary()->comment('乗務員CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('jyomuin_nm')->nullable()->comment('乗務員名');
            $table->integer('bumon_cd')->nullable()->comment('所属部門コード');
            $table->string('mobile_tel')->nullable()->comment('携帯電話');
            $table->string('mail')->nullable()->comment('メールアドレス');
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
        Schema::dropIfExists('m_jyomuin');
    }
};
