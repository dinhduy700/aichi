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
        Schema::create('t_zaiko_nyusyuko_meisai', function (Blueprint $table) {
            $table->integer('seq_no')->primary()->comment('id');
            $table->integer('zaiko_seq_no')->nullable()->comment('zaiko_id');
            $table->integer('nyusyuko_den_no')->nullable()->comment('伝票NO');
            $table->integer('nyusyuko_den_meisai_no')->nullable()->comment('伝票明細NO');
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
        Schema::dropIfExists('t_zaiko_nyusyuko_meisai');
    }
};
