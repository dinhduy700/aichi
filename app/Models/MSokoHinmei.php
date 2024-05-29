<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MSokoHinmei extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_soko_hinmei';

    protected $primaryKey = ['ninusi_cd', 'hinmei_cd'];
   
    protected $fillable = [
        'ninusi_cd',
        'hinmei_cd',
        'kana',
        'hinmei_nm',
        'kikaku',
        'ondo',
        'zaiko_kbn',
        'case_cd',
        'irisu',
        'hasu_kiriage',
        'bara_tani',
        'bara_tani_juryo',
        'uke_tanka',
        'seikyu_hinmei_cd',
        'keisan_kb',
        'seikyu_keta',
        'seikyu_bunbo',
        'nieki_nyuko_tanka',
        'nieki_syuko_tanka',
        'hokanryo_kin',
        'bumon_cd',
        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt',
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
        if ($request->filled('ninusi_cd')) {
            $query->where('m_soko_hinmei.ninusi_cd', 'ilike', makeEscapeStr($request->ninusi_cd).'%');
        }

        if ($request->filled('ninusi_nm')) {
            $query->where('m_ninusi.ninusi_ryaku_nm', 'ilike', makeEscapeStr($request->ninusi_nm).'%');
        }

        if ($request->filled('hinmei_cd')) {
            $query->where('m_soko_hinmei.hinmei_cd', 'ilike', makeEscapeStr($request->hinmei_cd).'%');
        }

        if ($request->filled('hinmei_nm')) {
            $query->where('m_soko_hinmei.hinmei_nm', 'ilike', makeEscapeStr($request->hinmei_nm).'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'm_soko_hinmei.kyumin_flg');
        }
    }
}