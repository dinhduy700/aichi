<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MBumon extends BaseModel
{
    use HasFactory;
    const CREATED_AT = 'add_dt';
    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_bumon';

    protected $primaryKey = 'bumon_cd';

    protected $fillable = [
        'bumon_cd',
        'kana',
        'bumon_nm',
        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt',
    ];

    public $timestamps = true;
    public $incrementing = false;

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

    public function scopeFilter ($query, $request) {
        if ($request->filled('bumon_cd')) {
            $query->where('bumon_cd', 'ilike', makeEscapeStr($request->bumon_cd) .'%');
        }

        if ($request->filled('bumon_nm')) {
            $query->where('bumon_nm', 'ilike', makeEscapeStr($request->bumon_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'kyumin_flg');
        }
    }
}
