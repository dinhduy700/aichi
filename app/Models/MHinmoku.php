<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MHinmoku extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_hinmoku';

    protected $primaryKey = 'hinmoku_cd';
   
    protected $fillable = [
        'hinmoku_cd',
        'kana',
        'hinmoku_nm',
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
        if ($request->filled('hinmoku_cd')) {
            $query->where('hinmoku_cd', 'ilike', makeEscapeStr($request->hinmoku_cd) .'%');
        }

        if ($request->filled('hinmoku_nm')) {
            $query->where('hinmoku_nm', 'ilike', makeEscapeStr($request->hinmoku_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'm_hinmoku.kyumin_flg');
        }
    }
}
