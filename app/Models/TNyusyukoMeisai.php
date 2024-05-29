<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class TNyusyukoMeisai extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_nyusyuko_meisai';

    protected $primaryKey = ['nyusyuko_den_no', 'nyusyuko_den_meisai_no'];

    protected $fillable = [
        'nyusyuko_den_no',
        'nyusyuko_den_meisai_no',
        'hinmei_cd',
        'lot1',
        'lot2',
        'lot3',
        'case_su',
        'hasu',
        'su',
        'tani_cd',
        'jyuryo',
        'soko_cd',
        'location',
        'biko_cd',
        'biko',
        'add_user_cd',
        'add_dt',
        'nyuko_dt',
        'seizo_no',
        'situryo',
        'nyusyuko_dt'
    ];

    public function getIncrementing()
    {
        return false;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->upd_user_cd = Auth::id();
            $model->upd_dt = \Carbon\Carbon::now();
            $model->add_user_cd = Auth::id();
            $model->add_dt = \Carbon\Carbon::now();
        });

        static::updating(function ($model) {
            $model->upd_user_cd = Auth::id();
            $model->upd_dt = \Carbon\Carbon::now();
            $oldAddDt = $model->getOriginal('add_dt');
            if ($oldAddDt !== null) {
                $model->add_dt = $oldAddDt;
            }
        });
    }
}   
