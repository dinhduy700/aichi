<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MHinmei extends BaseModel
{
    use HasFactory;
    const CREATED_AT = 'add_dt';
    const UPDATED_AT = 'upd_dt';
    protected $table = 'm_hinmei';

    protected $primaryKey = 'hinmei_cd';
   
    protected $fillable = [
        'hinmei_cd',
        'kana',
        'hinmei_nm',
        'hinmei2_cd',
        'hinmoku_cd',
        'tani_cd',
        'tani_jyuryo',
        'haisya_tani_jyuryo',
        'syoguti_kbn1',
        'syoguti_kbn2',
        'ninusi_id',
        'bumon_cd',
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
        if ($request->filled('hinmei_cd')) {
            $query->where('m_hinmei.hinmei_cd', 'ilike', makeEscapeStr($request->hinmei_cd) .'%');
        }

        if ($request->filled('hinmei_nm')) {
            $query->where('m_hinmei.hinmei_nm', 'ilike', makeEscapeStr($request->hinmei_nm) .'%');
        }

        if ($request->filled('hinmei_kana')) {
            $query->where('m_hinmei.kana', 'ilike', makeEscapeStr($request->hinmei_kana) .'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'm_hinmei.kyumin_flg');
        }

        if(isset($request->sort)) {
            $query->orderBy($request->sort, $request->order);
        } else {
            $query->orderBy('hinmei_cd', 'asc');
        }
    }
}
