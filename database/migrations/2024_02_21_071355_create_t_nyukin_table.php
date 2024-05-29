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
        Schema::create('t_nyukin', function (Blueprint $table) {
            $table->comment('入金データ');
            $table->integer('nyukin_no')->primary()->comment('入金NO');
            $table->date('nyukin_dt')->nullable()->comment('入金日');
            $table->integer('ninusi_cd')->nullable()->comment('荷主CD');
            $table->date('seikyu_sime_dt')->nullable()->comment('請求締日');
            $table->decimal('genkin_kin', 10, 0)->nullable()->comment('現金');
            $table->decimal('furikomi_kin', 10, 0)->nullable()->comment('振込');
            $table->decimal('furikomi_tesuryo_kin', 10, 0)->nullable()->comment('振込手数料');
            $table->decimal('tegata_kin', 10, 0)->nullable()->comment('手形');
            $table->date('tegata_kijitu_kin')->nullable()->comment('手形期日');
            $table->decimal('sousai_kin', 10, 0)->nullable()->comment('相殺');
            $table->decimal('nebiki_kin', 10, 0)->nullable()->comment('値引');
            $table->decimal('sonota_nyu_kin', 10, 0)->nullable()->comment('その他入金');
            $table->string('biko')->nullable()->comment('備考');
            // $table->bigInteger('seikyu_no')->nullable()->comment('請求NO');//update 13/03/2024
            $table->string('sime_kakutei_kbn', 1)->nullable()->comment('0：未確定、1:確定, 締日確定フラグ');
            $table->date('hikiate_simebi_dt')->nullable()->comment('引当締日');

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
        Schema::dropIfExists('t_nyukin');
    }
};
