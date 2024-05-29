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
        Schema::create('t_zaiko', function (Blueprint $table) {
            $table->integer('seq_no')->primary()->comment('id');
            $table->integer('bumon_cd')->nullable()->comment('部門CD');
            $table->integer('ninusi_cd')->nullable()->comment('荷主CD');
            $table->string('hinmei_cd')->nullable()->comment('品名CD');
            $table->integer('soko_cd')->nullable()->comment('倉庫CD');
            $table->string('location')->nullable()->comment('ロケーション');
            $table->string('lot1')->nullable()->comment('ロット１');
            $table->string('lot2')->nullable()->comment('ロット２');
            $table->string('lot3')->nullable()->comment('ロット３');
            $table->decimal('case_su', 10, 3)->nullable()->comment('ケース数');
            $table->decimal('hasu', 10, 3)->nullable()->comment('在庫端数');
            $table->decimal('su', 10, 3)->nullable()->comment('在庫総数');
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
        Schema::dropIfExists('t_zaiko');
    }
};
