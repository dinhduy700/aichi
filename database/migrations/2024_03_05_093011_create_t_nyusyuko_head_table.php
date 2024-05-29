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
        Schema::create('t_nyusyuko_head', function (Blueprint $table) {
            $table->comment('入出庫ヘッダデータ');
            $table->integer('nyusyuko_den_no')->primary()->comment('伝票NO');
            $table->integer('bumon_cd')->nullable()->comment('部門CD, 部門マスタと連携');
            $table->string('nyusyuko_kbn', 1)->nullable()->comment('入出庫区分, 1:入庫、2:出庫、3：スルー、4:棚卸、5：在庫移動、6：名義変更');
            $table->integer('ninusi_cd')->nullable()->comment('荷主CD');
            $table->integer('hachaku_cd')->nullable()->comment('発着地CD　※着地CD, 発地着地マスタと連携　着地CD／荷届け先の意味');
            $table->string('todokesaki_nm')->nullable()->comment('　届け先名, 着地CDの入力/未入力にかかわらず自由に入力可能');
            $table->string('haitatu_jyusyo1')->nullable()->comment('　配達住所１');
            $table->string('haitatu_jyusyo2')->nullable()->comment('　配達住所２');
            $table->string('haitatu_atena')->nullable()->comment('　配達宛名');
            $table->string('haitatu_tel')->nullable()->comment('　配達TEL');
            $table->integer('hatuti_cd')->nullable()->comment('発地CD, 発地着地マスタと連携　発地CD／荷送り人の意味');
            $table->string('hatuti_nm')->nullable()->comment('　発地名, 発地CDの入力/未入力にかかわらず自由に入力可能');
            $table->string('hatuti_jyusyo1')->nullable()->comment('　発地住所１');
            $table->string('hatuti_jyusyo2')->nullable()->comment('　発地住所２');
            $table->string('hatuti_atena')->nullable()->comment('　発地宛名');
            $table->string('hatuti_tel')->nullable()->comment('　発地TEL');
            $table->date('denpyo_dt')->nullable()->comment('伝票日付');
            $table->date('kisan_dt')->nullable()->comment('起算日');
            $table->date('nouhin_dt')->nullable()->comment('納品日');
            $table->string('nieki_futan_kbn', 1)->nullable()->comment('荷役料負担区分, 1：有償、2：無償');
            $table->boolean('denpyo_print_kbn')->nullable()->comment('　更新時に伝票発行区分, false、true');
            $table->boolean('syamei_print_kbn')->nullable()->comment('　社名印字無し区分, false、true');
            $table->boolean('nouhinsyo_kbn')->nullable()->comment('　納品書必要区分, false、true');
            $table->string('soryo_kbn', 1)->nullable()->comment('　送料区分, 0:無し、1：有り');
            $table->string('syaban')->nullable()->comment('　車番');
            $table->integer('jyomuin_cd')->nullable()->comment('　乗務員CD, 乗務員マスタと連携');
            $table->integer('yousya_cd')->nullable()->comment('　庸車先CD, 庸車先マスタと連携');
            $table->string('tekiyo')->nullable()->comment('　摘要名');

            $table->integer('uriage_den_no')->nullable()->comment('売上データに連携');
            $table->string('okurijyo_no')->nullable()->comment('送り状番号');
            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_nyusyuko_head');
    }
};
