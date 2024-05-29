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
        Schema::create('m_soko_hinmei', function (Blueprint $table) {
            $table->comment('倉庫商品マスタ');
            $table->integer('ninusi_cd')->comment('荷主CD');
            $table->string('hinmei_cd')->comment('品名CD');
            $table->string('kana')->nullable()->comment('ヨミガナ');
            $table->string('hinmei_nm')->nullable()->comment('名称');
            $table->string('kikaku')->nullable()->comment('商品企画');
            $table->string('ondo', 1)->nullable()->comment('温度帯, 0：無し、1：常温、2：冷蔵、3：冷凍');
            $table->string('zaiko_kbn', 1)->nullable()->comment('在庫区分, 0:在庫品、1：スルー品');
            $table->string('case_cd')->nullable()->comment('ケース単位コード');
            $table->decimal('irisu', 10, 0)->nullable()->comment('入り数');
            $table->decimal('hasu_kiriage', 10, 0)->nullable()->comment('端数切り上げ数');
            $table->string('bara_tani')->nullable()->comment('バラ単位コード');
            $table->decimal('bara_tani_juryo', 10, 3)->nullable()->comment('バラ単位コード重量');
            $table->decimal('uke_tanka', 10, 3)->nullable()->comment('受寄物単価');
            $table->integer('seikyu_hinmei_cd')->nullable()->comment('請求品名コード');
            $table->string('keisan_kb', 1)->nullable()->comment('請求額計算区分, 0：数量*単価、1：重量*単価、2：重量/1000*単価、3：ケース*単価');
            $table->string('seikyu_keta', 1)->nullable()->comment('請求書印字少数桁, 0：小数点無し、1：小数点１桁、2：小数点２桁、3：小数点３桁');
            $table->decimal('seikyu_bunbo', 10, 0)->nullable()->comment('請求書印字分母');
            $table->decimal('nieki_nyuko_tanka', 10, 2)->nullable()->comment('荷役単価（入庫）');
            $table->decimal('nieki_syuko_tanka', 10, 2)->nullable()->comment('荷役単価（出庫）');
            $table->decimal('hokanryo_kin', 10, 2)->nullable()->comment('保管料単価');
            $table->integer('bumon_cd')->nullable()->comment('部門CD');
            $table->string('kyumin_flg', 1)->nullable()->comment('休眠フラグ, 0：通常、１：休眠とし、検索画面で非表示');
            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['ninusi_cd', 'hinmei_cd']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_soko_hinmei');
    }
};
