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
        Schema::create('t_uriage', function (Blueprint $table) {
            $table->integer('uriage_den_no')->primary()->comment('伝票NO');
            $table->integer('bumon_cd')->nullable()->comment('部門CD, 部門マスタと連携');
            $table->integer('hatuti_cd')->nullable()->comment('発地CD');
            $table->string('hatuti_hachaku_nm')->nullable();
            $table->string('genkin_cd', 255)->nullable()->comment('現金CD, 1:現金、　それ以外は無し');
            $table->integer('ninusi_cd')->nullable()->comment('荷主CD');
            $table->date('syuka_dt')->nullable()->comment('集荷日, yyyy/mm/dd');
            $table->string('syuka_tm')->nullable()->comment('集荷指定時間(配達時刻）, MM:ss');
            $table->date('haitatu_dt')->nullable()->comment('配達日, yyyy/mm/dd');
            $table->integer('hachaku_cd')->nullable()->comment('発着地CD, 発地着地マスタと連携');
            $table->string('hachaku_nm')->nullable();
            $table->string('syubetu_cd')->nullable()->comment('商品種別, 名称マスタの区分「syubetu」利用');
            $table->integer('hinmei_cd')->nullable()->comment('品名CD');
            $table->string('hinmei_nm')->nullable();
            $table->decimal('menzei_kbn', 10, 0)->nullable()->comment('免税区分, 0:課税、1:免税');
            $table->decimal('seikyu_kin_tax', 10, 0)->nullable()->comment('消費税');
            $table->decimal('nieki_kin', 10, 0)->nullable()->comment('荷役料');
            $table->decimal('su', 10, 3)->nullable()->comment('数量');
            $table->string('tani_cd')->nullable()->comment('単位CD, 名称マスタと連携');
            $table->date('unso_dt')->nullable()->comment('運送日, yyyy/mm/dd');
            $table->string('jyotai')->nullable()->comment('状態');
            $table->string('sitadori')->nullable()->comment('下取り');
            $table->string('gyosya_cd')->nullable()->comment('業者CD, 名称マスタと連携');
            $table->string('unchin_mikakutei_kbn', 255)->nullable()->comment('　運賃未確定区分, 0:確定、1:未確定、9：請求なし');
            $table->decimal('unchin_kin', 10, 0)->nullable()->comment('　基本運賃');
            $table->decimal('tyukei_kin', 10, 0)->nullable()->comment('　中継料');
            $table->decimal('tukoryo_kin', 10, 0)->nullable()->comment('　通行料等');
            $table->decimal('syuka_kin', 10, 0)->nullable()->comment('　集荷料');
            $table->decimal('tesuryo_kin', 10, 0)->nullable()->comment('　手数料');
            $table->date('seikyu_keijyo_dt')->nullable()->comment('　請求計上日, yyyy/mm/dd');
            $table->date('seikyu_sime_dt')->nullable()->comment('　請求締日, yyyy/mm/dd');
            // 支払明細
            $table->string('yosya_kin_mikakutei_kbn', 1)->nullable()->comment('　庸車料未確定区分, 0:確定、1:未確定');
            $table->decimal('yosya_tyukei_kin', 10, 0)->nullable()->comment('　庸車料');
            $table->decimal('yosya_tukoryo_kin', 10, 0)->nullable()->comment('　通行料');
            $table->decimal('yosya_kin_tax', 10, 0)->nullable()->comment('　消費税');
            $table->date('yousya_keijyo_dt')->nullable()->comment('　支払計上日, yyyy/mm/dd  配達日をセット');//07_AICHI_KOUSOKU_UNYU-183#comment-21216929
            $table->date('yousya_sime_dt')->nullable()->comment('　支払締日, yyyy/mm/dd');

            $table->string('syaban')->nullable()->comment('車番');
            $table->integer('jyomuin_cd')->nullable()->comment('運転者CD, 乗務員マスタと連携');
            $table->integer('yousya_cd')->nullable()->comment('庸車先CD, 庸車先マスタと連携');
            $table->decimal('syaryo_kin', 10, 0)->nullable()->comment('車両金額');
            $table->decimal('unten_kin', 10, 0)->nullable()->comment('運転者金額');

            $table->date('denpyo_send_dt')->nullable()->comment('伝票送付日, yyyy/mm/dd');
            $table->date('nipou_dt')->nullable()->comment('日報日, yyyy/mm/dd');
            $table->integer('nipou_no')->nullable()->comment('日報NO');
            $table->integer('biko_cd')->nullable()->comment('備考CD, 備考マスタと連携');
            $table->string('biko')->nullable()->comment('備考');

            // 受注入力利用項目
            $table->string('tyuki')->nullable()->comment('配達注記');
            $table->string('tanka_kbn')->nullable()->comment('単価区分');
            $table->decimal('seikyu_tanka', 10, 2)->nullable()->comment('請求単価');
            //$table->decimal('yosya_tyukei_kin', 10, 0)->nullable()->comment('庸車料');
            //$table->decimal('yosya_tukoryo_kin', 10, 0)->nullable()->comment('庸車通行料等');
            $table->string('okurijyo_no')->nullable()->comment('送り状番号');
            $table->string('jyutyu_kbn')->nullable()->comment('受注区分, 名称マスタと連携');
            $table->date('kaisyu_dt')->nullable()->comment('回収日');
            $table->decimal('kaisyu_kin', 10, 0)->nullable()->comment('回収金額');
            //$table->decimal('tukoryo_kin', 10, 0)->nullable()->comment('通行料等');
            $table->integer('add_tanto_cd')->nullable()->comment('入力担当CD, 乗務員マスタと連携');
            $table->string('add_tanto_nm')->nullable()->comment('入力担当名, 乗務員マスタと連携');
            $table->string('haitatu_tel')->nullable()->comment('配達TEL');
            // $table->time('jikoku')->nullable()->comment('配達時刻, 00:00～23:59');
            $table->string('jikoku')->nullable()->comment('配達時刻, 00:00～23:59');
            $table->string('haitatu_jyusyo1')->nullable()->comment('配達住所１');
            $table->string('haitatu_jyusyo2')->nullable()->comment('配達住所２');
            $table->string('haitatu_atena')->nullable()->comment('配達宛名');
            $table->string('haitatu_fax')->nullable()->comment('配達FAX');
            $table->decimal('jisya_km', 10, 0)->nullable()->comment('実車Km');

            // update 13/03/2024
            $table->bigInteger('seikyu_no')->nullable()->comment('請求NO');
            $table->string('sime_kakutei_kbn', 1)->nullable()->comment('締日確定フラグ');
            // end update 13/03/2024

            $table->string('add_user_cd')->nullable()->comment('登録者');
            $table->timestamp('add_dt')->nullable()->comment('登録日');
            $table->string('upd_user_cd')->nullable()->comment('更新者');
            $table->timestamp('upd_dt')->nullable()->comment('更新日');

            // update when star 15.入出庫入力 20/3/2024
            $table->string('souryo_kbn', 1)->nullable()->comment('0:無し、1：有り');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_uriage');
    }
};
