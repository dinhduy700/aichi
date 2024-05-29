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
        Schema::create('m_user_pg_function', function (Blueprint $table) {
            $table->comment('ユーザ毎設定マスタ');
            $table->string('user_cd')->comment('ユーザID, 例）endo');
            $table->string('pg_nm')->comment('プログラム名, 例）uriage_entry');
            $table->string('function')->comment('機能, 例）複写列選択');

            for($i=1; $i<=100; $i++) {
                $table->string("choice{$i}_nm")->nullable()->comment("選択名{$i}, 例）受注部門");
                $table->boolean("choice{$i}_bool")->nullable()->comment("選択{$i}_bool, 0:選択無し、1:選択（チェックした）");
                $table->string("choice{$i}_char")->nullable()->comment("選択{$i}_char");
                $table->date("choice{$i}_dt")->nullable()->comment("選択{$i}_dt");
                $table->decimal("choice{$i}_num", 10, 3)->nullable()->comment("選択{$i}_num");
            }

            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            $table->primary(['user_cd', 'pg_nm', 'function']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_pg_function');
    }
};
