<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('m_hachaku', function (Blueprint $table) {
            $table->comment('発着地マスタ');
            $table->integer('hachaku_cd')->primary()->comment('発着地CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('hachaku_nm')->nullable()->comment('名称');
            $table->integer('atena_ninusi_id')->nullable()->comment('宛名荷主CD, 荷主マスタと連携');
            $table->string('atena')->nullable()->comment('宛名');
            $table->string('jyusyo1_nm')->nullable()->comment('住所１');
            $table->string('jyusyo2_nm')->nullable()->comment('住所２');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('fax')->nullable()->comment('FAX番号');
            $table->integer('ninusi_id')->nullable()->comment('荷主CD, 荷主マスタと連携');
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
        Schema::dropIfExists('m_hachaku');
    }
};
