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
        Schema::create('m_meisyo', function (Blueprint $table) {
            $table->comment('名称マスタ');
            $table->string('meisyo_kbn')->comment('名称区分, tani（単位）、syubetu（種別）、syasyu（車種）、gyosya（業者）、rikuun(陸運支局)');
            $table->string('meisyo_cd')->comment('名称CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('meisyo_nm')->nullable()->comment('名称');
            $table->decimal('jyuryo_kansan', 10, 3)->nullable()->comment('重量換算係, 単位メンテ専用');
            $table->string('sekisai_kbn', 1)->nullable()->comment('積載区分, 車種メンテ専用');
            $table->string('kyumin_flg', 1)->nullable()->comment('休眠フラグ, 0：通常、１：休眠とし、検索画面で非表示');
            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['meisyo_kbn', 'meisyo_cd']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_meisyo');
    }
};
