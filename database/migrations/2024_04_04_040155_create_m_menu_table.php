<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_menu', function (Blueprint $table) {
            $table->comment('メニーマスタ');
            $table->string('user_cd')->primary()->comment('ユーザID');
            $table->string('pgid1')->nullable()->comment('メニュー１');
            $table->string('pgid2')->nullable()->comment('メニュー２');
            $table->string('pgid3')->nullable()->comment('メニュー３');
            $table->string('pgid4')->nullable()->comment('メニュー４');
            $table->string('pgid5')->nullable()->comment('メニュー５');
            $table->string('pgid6')->nullable()->comment('メニュー６');
            $table->string('pgid7')->nullable()->comment('メニュー７');
            $table->string('pgid8')->nullable()->comment('メニュー８');
            $table->string('pgid9')->nullable()->comment('メニュー９');
            $table->string('pgid10')->nullable()->comment('メニュー１０');
            $table->string('pgid11')->nullable()->comment('メニュー１１');
            $table->string('pgid12')->nullable()->comment('メニュー１２');
            $table->string('pgid13')->nullable()->comment('メニュー１３');
            $table->string('pgid14')->nullable()->comment('メニュー１４');
            $table->string('pgid15')->nullable()->comment('メニュー１５');
            $table->string('pgid16')->nullable()->comment('メニュー１６');
            $table->string('pgid17')->nullable()->comment('メニュー１７');
            $table->string('pgid18')->nullable()->comment('メニュー１８');
            $table->string('pgid19')->nullable()->comment('メニュー１９');
            $table->string('pgid20')->nullable()->comment('メニュー２０');
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
        Schema::dropIfExists('m_menu');
    }
};
