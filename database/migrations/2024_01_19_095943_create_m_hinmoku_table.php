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
        Schema::create('m_hinmoku', function (Blueprint $table) {
            $table->comment('品目マスタ');
            $table->integer('hinmoku_cd')->primary()->comment('品目CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('hinmoku_nm')->nullable()->comment('名称');
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
        Schema::dropIfExists('m_hinmoku');
    }
};
