<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class TNyusyukoHead extends Model
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_nyusyuko_head';

    protected $primaryKey = 'nyusyuko_den_no';

    protected $fillable = [
        'nyusyuko_den_no',
        'bumon_cd',
        'nyusyuko_kbn',
        'ninusi_cd',
        'hachaku_cd',
        'todokesaki_nm',
        'haitatu_jyusyo1',
        'haitatu_jyusyo2',
        'haitatu_atena',
        'haitatu_tel',
        'hatuti_cd',
        'hatuti_nm',
        'hatuti_jyusyo1',
        'hatuti_jyusyo2',
        'hatuti_atena',
        'hatuti_tel',
        'denpyo_dt',
        'kisan_dt',
        'nouhin_dt',
        'nieki_futan_kbn',
        'denpyo_print_kbn',
        'syamei_print_kbn',
        'nouhinsyo_kbn',
        'soryo_kbn',
        'syaban',
        'jyomuin_cd',
        'yousya_cd',
        'tekiyo',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt',
        'uriage_den_no',
        'okurijyo_no'
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

    public function scopeJoinMeisai($query, $type = 'inner', $joinAlias = 't_nyusyuko_meisai')
    {
        $query->join("t_nyusyuko_meisai AS {$joinAlias}", "{$this->table}.nyusyuko_den_no", '=', "{$joinAlias}.nyusyuko_den_no", $type);
    }
}
