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
        Schema::create('t_seikyu_nyukin', function (Blueprint $table) {
            $table->comment('請求入金対応データ');
            $table->integer('seq_no')->primary()->comment('id');
            $table->bigInteger('seikyu_no')->nullable()->comment('請求NO');
            $table->integer('nyukin_no')->nullable()->comment('入金NO');
            $table->decimal('nyukin_kin', 10, 0)->nullable()->comment('入金額');
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
        Schema::dropIfExists('t_seikyu_nyukin');
    }
};
