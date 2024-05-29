<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MHachaku extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_hachaku';

    protected $primaryKey = ['hachaku_cd'];
   
    protected $fillable = [
        'hachaku_cd',
        'kana',
        'hachaku_nm',
        'atena_ninusi_id',
        'atena',
        'jyusyo1_nm',
        'jyusyo2_nm',
        'tel',
        'fax',
        'ninusi_id',
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
        if ($request->filled('hachaku_cd')) {
            $query->where('m_hachaku.hachaku_cd', 'ilike', makeEscapeStr($request->hachaku_cd) .'%');
        }

        if ($request->filled('hachaku_nm')) {
            $query->where('m_hachaku.hachaku_nm', 'ilike', makeEscapeStr($request->hachaku_nm) .'%');
        }

        if($request->has('kyumin_flg')) {
            $query->where('m_hachaku.kyumin_flg', $request->kyumin_flg);
        }
    }
}