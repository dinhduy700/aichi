<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class MNinusi extends BaseModel
{
    use HasFactory;
    protected $table = 'm_ninusi';

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $primaryKey = ['ninusi_cd'];
    protected $fillable = [
        'ninusi_cd',
        'kana',
        'ninusi1_nm',
        'ninusi2_nm',
        'ninusi_ryaku_nm',
        'bumon_cd',
        'bumon_nm',
        'yubin_no',
        'jyusyo1_nm',
        'jyusyo2_nm',
        'tel',
        'fax',
        'seikyu_kbn',
        'seikyu_cd',
        'seikyu_nm',
        'seikyu_mu_kbn',
        'simebi1',
        'simebi2',
        'simebi3',
        'mikakutei_seigyo_kbn',
        'kin_hasu_kbn',
        'kin_hasu_tani',
        'zei_keisan_kbn',
        'zei_hasu_kbn',
        'zei_hasu_tani',
        'urikake_saki_cd',
        'urikake_saki_nm',
        'nyukin_umu_kbn',
        'kaisyu1_dd',
        'kaisyu2_dd',
        'comennt',
        'seikyu_teigi_no',
        'unchin_teigi_no',
        'kensaku_kbn',
        'unso_bi_kbn',
        'nebiki_ritu',
        'nebiki_hasu_kbn',
        'nebiki_hasu_tani',
        'mail',
        'okurijyo_hako_kbn',
        'biko',

        'lot_kanri_kbn',
        'lot1_nm',
        'lot2_nm',
        'lot3_nm',
        'kisei_kbn',
        'ki1_from',
        'ki1_to',
        'ki2_from',
        'ki2_to',
        'ki3_from',
        'ki3_to',
        'sekisu_kbn',
        'soko_hokan_hasu_kbn',
        'soko_hokan_hasu_tani',
        'hokanryo_meisyo',
        'nieki_sansyutu_kbn',
        'nieki_hokan_hasu_kbn',
        'nieki_hokan_hasu_tani',
        'nieki_nyuko_nm',
        'nieki_syuko_nm',
        'nieki_nieki_nm',
        'soko_seikyu_cd',
        'soko_bumon_cd',
        'nyuko_tanka',
        'syuko_tanka',
        'hokan_tanka',

        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];

    public $timestamps = false;

    public function getIncrementing()
    {
        return false;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->upd_user_cd = Auth::id();
            $model->add_user_cd = Auth::id();
        });

        static::updating(function ($model) {
            $model->upd_user_cd = Auth::id();
        });
    }


    public function scopeFilter($query, $request)
    {
        if ($request->filled('ninusi_cd')) {
            $query->where($this->table . '.ninusi_cd',  'ilike', makeEscapeStr($request->ninusi_cd) . '%');
        }

        if ($request->filled('ninusi_ryaku_nm')) {
            $query->where($this->table . '.ninusi_ryaku_nm',  'ilike', makeEscapeStr($request->ninusi_ryaku_nm) . '%');
        }

        if ($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, $this->table . '.kyumin_flg');
        }
    }
}
