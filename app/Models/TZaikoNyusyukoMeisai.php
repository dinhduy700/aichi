<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TZaikoNyusyukoMeisai extends Model
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_zaiko_nyusyuko_meisai';

    protected $primaryKey = 'seq_no';

    protected $fillable = [
        'seq_no',
        'zaiko_seq_no',
        'nyusyuko_den_no',
        'nyusyuko_den_meisai_no',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];

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
