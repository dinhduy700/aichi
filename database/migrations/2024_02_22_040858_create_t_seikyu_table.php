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
        Schema::create('t_seikyu', function (Blueprint $table) {
            $table->comment('請求データ');
            $table->integer('ninusi_cd')->comment('荷主CD');
            $table->date('seikyu_sime_dt')->comment('請求締日');
            $table->bigInteger('seikyu_no')->nullable()->comment('請求NO');
            $table->decimal('zenkai_seikyu_kin', 10, 0)->nullable()->comment('前回請求額');
            // 入金額内訳
            $table->decimal('genkin_kin', 10, 0)->nullable()->comment('入金・現金');
            $table->decimal('furikomi_kin', 10, 0)->nullable()->comment('入金・振込');
            $table->decimal('furikomi_tesuryo_kin', 10, 0)->nullable()->comment('入金・振込手数料');
            $table->decimal('tegata_kin', 10, 0)->nullable()->comment('入金・手形');
            $table->decimal('sousai_kin', 10, 0)->nullable()->comment('入金・相殺');//==
            $table->decimal('nebiki_kin', 10, 0)->nullable()->comment('入金・値引');//==
            $table->decimal('sonota_nyu_kin', 10, 0)->nullable()->comment('入金・その他');
            $table->decimal('kjrikosi_kin', 10, 0)->nullable()->comment('繰越額');

            // 今回取引額内訳
            $table->decimal('kazei_unchin_kin', 10, 0)->nullable()->comment('課税運賃');
            $table->decimal('kazei_tyukei_kin', 10, 0)->nullable()->comment('中継料（課税）');
            $table->decimal('kazei_tukouryou_kin', 10, 0)->nullable()->comment('通行料等（課税）');
            $table->decimal('kazei_niyakuryo_kin', 10, 0)->nullable()->comment('荷役料（課税）');

            $table->decimal('zei_kin', 10, 0)->nullable()->comment('消費税');

            $table->decimal('hikazei_unchin_kin', 10, 0)->nullable()->comment('基本運賃（非課税）');
            $table->decimal('hikazei_tyukei_kin', 10, 0)->nullable()->comment('中継料（非課税）');
            $table->decimal('hikazei_tukouryo_kin', 10, 0)->nullable()->comment('通行料等（非課税）');
            $table->decimal('hikazei_niyakuryo_kin', 10, 0)->nullable()->comment('荷役料（非課税）');

            $table->decimal('konkai_torihiki_kin', 10, 0)->nullable()->comment('今回取引額');
            $table->string('seikyu_hako_flg', 1)->nullable()->comment('請求書発行FLG, 0:未発行　1:発行済');
            $table->string('seikyu_kakutei_flg', 1)->nullable()->comment('請求確定FLG, 0:未確定　1:確定済');

            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['ninusi_cd', 'seikyu_sime_dt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_seikyu');
    }
};
