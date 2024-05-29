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
        Schema::create('t_zaiko_kijyun', function (Blueprint $table) {
            $table->comment('在庫基準データ');
            $table->integer('bumon_cd')->comment('部門CD, 部門マスタと連携');
            $table->date('kijyun_dt')->comment('基準日, 例）2023/05/31');
            $table->integer('ninusi_cd')->comment('荷主CD');
            $table->string('hinmei_cd')->comment('品名CD');
            $table->string('location')->comment('ロケーション, 空文字は許可');
            $table->decimal('case_su', 10, 3)->nullable()->comment('ケース数（入数）, 23　　倉庫商品マスタでは「入数」');
            $table->decimal('hasu_su', 10, 3)->nullable()->comment('端数, 13');
            $table->decimal('zaiko_all_su', 10, 3)->nullable()->comment('在庫総数, 23*45+13=1048');
            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['kijyun_dt', 'ninusi_cd', 'hinmei_cd', 'location']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_zaiko_kijyun');
    }
};
