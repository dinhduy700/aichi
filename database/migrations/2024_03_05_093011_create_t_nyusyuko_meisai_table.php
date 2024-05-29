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
        Schema::create('t_nyusyuko_meisai', function (Blueprint $table) {
            $table->comment('入出庫明細データ');
            $table->integer('nyusyuko_den_no')->comment('伝票NO');
            $table->integer('nyusyuko_den_meisai_no')->comment('伝票明細NO');
            $table->string('hinmei_cd')->nullable()->comment('品名CD');
            $table->string('lot1')->nullable()->comment('ロット１');
            $table->string('lot2')->nullable()->comment('ロット２');
            $table->string('lot3')->nullable()->comment('ロット３');
            $table->decimal('case_su', 10, 3)->nullable()->comment('ケース数');
            $table->decimal('hasu', 10, 3)->nullable()->comment('端数');
            $table->decimal('su', 10, 3)->nullable()->comment('数量');
            $table->string('tani_cd')->nullable()->comment('単位CD, 名称マスタと連携');
            $table->decimal('jyuryo', 10, 3)->nullable()->comment('重量／㎥');
            $table->integer('soko_cd')->nullable()->comment('倉庫CD');
            $table->string('location')->nullable()->comment('ロケーション');
            $table->string('biko')->nullable()->comment('備考');
            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['nyusyuko_den_no', 'nyusyuko_den_meisai_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_nyusyuko_meisai');
    }
};
