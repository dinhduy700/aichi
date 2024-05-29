<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MJyomuin extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_jyomuin';

    protected $primaryKey = 'jyomuin_cd';

    protected $fillable = [
        'jyomuin_cd',
        'kana',
        'jyomuin_nm',
        'bumon_cd',
        'mobile_tel',
        'mail',
        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
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

    public function scopeFilter($query, $request)
    {
        if ($request->filled('jyomuin_cd')) {
            $query->where('jyomuin_cd', 'ilike', makeEscapeStr($request->jyomuin_cd) .'%');
        }

        if ($request->filled('jyomuin_nm')) {
            $query->where('jyomuin_nm', 'ilike', makeEscapeStr($request->jyomuin_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'm_jyomuin.kyumin_flg');
        }
    }
}
