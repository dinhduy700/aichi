<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class MYousya extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_yousya';

    protected $primaryKey = ['yousya_cd'];

    protected $fillable = [
        'yousya_cd',
        'kana',
        'yousya1_nm',
        'yousya2_nm',
        'yousya_ryaku_nm',
        'bumon_cd',
        'yubin_no',
        'jyusyo1_nm',
        'jyusyo2_nm',
        'tel',
        'fax',
        'siharai_kbn',
        'siharai_cd',
        'yousya_ritu',
        'siharai_umu_kbn',
        'siharai_kbn',
        'simebi1',
        'simebi2',
        'simebi3',
        'mikakutei_seigyo_kbn',
        'kin_hasu_kbn',
        'kin_hasu_tani',
        'zei_keisan_kbn',
        'zei_hasu_kbn',
        'zei_hasu_tani',
        'kaikake_saki_cd',
        'siharai_nyuryoku_umu_kbn',
        'siharai1_dd',
        'siharai2_dd',
        'comennt',
        'kensaku_kbn',
        'mail',
        'haisya_biko',
        'biko',
        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];

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

    public function getIncrementing()
    {
        return false;
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('yousya_cd')) {
            $query->where('m_yousya.yousya_cd', 'ilike', makeEscapeStr($request->yousya_cd) .'%');
        }

        if ($request->filled('yousya_ryaku_nm')) {
            $query->where('m_yousya.yousya_ryaku_nm', 'ilike', '%' . makeEscapeStr($request->yousya_ryaku_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            $query->where('m_yousya.kyumin_flg', $request->kyumin_flg);
        }
    }
}
