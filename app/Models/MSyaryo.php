<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MSyaryo extends BaseModel
{
    use HasFactory;
    protected $table = 'm_syaryo';

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $primaryKey = ['syaryo_cd'];
    protected $fillable = [
        'syaryo_cd',
        'syasyu_cd',
        'jiyo_kbn',
        'jyomuin_cd',
        'yousya_cd',
        'bumon_cd',
        'sekisai_kbn',
        'sekisai_jyuryo',
        'point',
        'himoku_ritu',
        'haisya_dt',
        'rikuun_cd',
        'car_number_syubetu',
        'car_number_kana',
        'car_number',
        'haisya_biko',
        'biko',
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
        if ($request->filled('syaryo_cd')) {
            $query->where('syaryo_cd',  'ilike', makeEscapeStr($request->syaryo_cd) . '%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'm_syaryo.kyumin_flg');
        }
    }
}
