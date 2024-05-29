<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MSoko extends BaseModel
{
    use HasFactory;
    const CREATED_AT = 'add_dt';
    const UPDATED_AT = 'upd_dt';
    protected $table = 'm_soko';

    protected $primaryKey = ['bumon_cd','soko_cd'];
   
    protected $fillable = [
        'bumon_cd',
        'soko_cd',
        'kana',
        'soko_nm',
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

    public function scopeFilter ($query, $request) {
        if ($request->filled('soko_cd')) {
            $query->where('m_soko.soko_cd', 'ilike', makeEscapeStr($request->soko_cd) .'%');
        }

        if ($request->filled('soko_nm')) {
            $query->where('m_soko.soko_nm', 'ilike', makeEscapeStr($request->soko_nm) .'%');
        }

        if ($request->filled('bumon_cd')) {
            $query->where('m_soko.bumon_cd', 'ilike', makeEscapeStr($request->bumon_cd) .'%');
        }

        if ($request->filled('bumon_nm')) {
            $query->where('m_bumon.bumon_nm', 'ilike', makeEscapeStr($request->bumon_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'm_soko.kyumin_flg');
        }
    }
}
