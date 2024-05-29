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
        Schema::create('t_zaiko_hokanryo', function (Blueprint $table) {
            $table->comment('保管料荷役料計算データ');
            $table->integer('bumon_cd')->comment('部門CD');
            $table->integer('ninusi_cd')->comment('荷主CD');
            $table->date('seikyu_sime_dt')->comment('請求締日');
            $table->bigInteger('seikyu_no')->nullable()->comment('請求NO');
            $table->string('hinmei_cd')->comment('品名CD');

            $table->decimal('ki1_kurikosi_su', 10, 3)->nullable()->comment('１期　繰越');
            $table->decimal('ki1_nyuko_su', 10, 3)->nullable()->comment('１期　入庫');
            $table->decimal('ki1_syuko_su', 10, 3)->nullable()->comment('１期　出庫');

            $table->decimal('ki2_kurikosi_su', 10, 3)->nullable()->comment('２期　繰越');
            $table->decimal('ki2_nyuko_su', 10, 3)->nullable()->comment('２期　入庫');
            $table->decimal('ki2_syuko_su', 10, 3)->nullable()->comment('２期　出庫');

            $table->decimal('ki3_kurikosi_su', 10, 3)->nullable()->comment('３期　繰越');
            $table->decimal('ki3_nyuko_su', 10, 3)->nullable()->comment('３期　入庫');
            $table->decimal('ki3_syuko_su', 10, 3)->nullable()->comment('３期　出庫');
            $table->decimal('touzan_su', 10, 3)->nullable()->comment('当月残数');// chưa có mô tả trong spec

            $table->decimal('seki_su', 10, 3)->nullable()->comment('積数');
            $table->decimal('tanka', 10, 3)->nullable()->comment('単価');
            $table->decimal('hokan_kin', 10, 3)->nullable()->comment('保管料');

            $table->decimal('nyuko_su', 10, 3)->nullable()->comment('入庫数');
            $table->decimal('nyuko_tanka', 10, 3)->nullable()->comment('入庫単価');
            $table->decimal('nyuko_kin', 10, 3)->nullable()->comment('入庫料');
            $table->decimal('syuko_su', 10, 3)->nullable()->comment('出庫数');
            $table->decimal('syuko_tanka', 10, 3)->nullable()->comment('出庫単価');
            $table->decimal('syuko_kin', 10, 3)->nullable()->comment('出庫料');
            $table->decimal('total_kin', 10, 3)->nullable()->comment('合計金額');

            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['bumon_cd', 'ninusi_cd', 'seikyu_sime_dt', 'hinmei_cd']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_zaiko_hokanryo');
    }
};
