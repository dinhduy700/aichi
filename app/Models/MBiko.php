<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MBiko extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_biko';

    protected $primaryKey = ['biko_cd'];
   
    protected $fillable = [
        'biko_cd',
        'kana',
        'biko_nm',
        'syubetu_kbn',
        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];

    // public $timestamps = false;

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
        if ($request->filled('biko_cd')) {
            $query->where('m_biko.biko_cd', 'ilike', makeEscapeStr($request->biko_cd) .'%');
        }

        if ($request->filled('biko_nm')) {
            $query->where('m_biko.biko_nm', 'ilike', makeEscapeStr($request->biko_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            $query->where('kyumin_flg', $request->kyumin_flg);
        }
    }
}