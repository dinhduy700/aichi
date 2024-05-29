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
        Schema::create('m_syaryo', function (Blueprint $table) {
            $table->comment('車両マスタ');
            $table->string('syaryo_cd')->primary()->comment('車両CD');
            $table->string('syasyu_cd')->nullable()->comment('車種CD');
            $table->string('jiyo_kbn', 1)->nullable()->comment('自庸区分');
            $table->integer('jyomuin_cd')->nullable()->comment('乗務員CD');
            $table->integer('yousya_cd')->nullable()->comment('庸車CD');
            $table->integer('bumon_cd')->nullable()->comment('部門CD');
            $table->string('sekisai_kbn', 1)->nullable()->comment('積載区分,0:5ｔまで、1：5ｔ以上');
            $table->decimal('sekisai_jyuryo', 10, 3)->nullable()->comment('積載重量');
            $table->decimal('point', 10, 1)->nullable()->comment('ポイント');
            $table->decimal('himoku_ritu', 10, 1)->nullable()->comment('自動計算用率');
            $table->date('haisya_dt')->nullable()->comment('廃車日付');
            $table->string('rikuun_cd')->nullable()->comment('陸運支局CD,名称マスタと連携');
            $table->string('car_number_syubetu')->nullable()->comment('種別');
            $table->string('car_number_kana')->nullable()->comment('かな');
            $table->string('car_number')->nullable()->comment('ナンバー');
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
        Schema::dropIfExists('m_syaryo');
    }
};
