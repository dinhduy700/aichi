<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TSeikyu extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_seikyu';

    protected $primaryKey = ['ninusi_cd', 'seikyu_sime_dt'];

    protected $fillable = [
        'ninusi_cd',
        'seikyu_sime_dt',
        'seikyu_no',
        'zenkai_seikyu_kin',
        'nyukin_kin',
        'sousai_kin',
        'nebiki_kin',
        'kjrikosi_kin',
        'kazei_unchin_kin',
        'zei_kin',
        'hikazei_kin',
        'konkai_torihiki_kin',
        'seikyu_hako_flg',
        'seikyu_kakutei_flg',
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
        $fields = ['ninusi_cd'];
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $query->where($this->table . ".{$field}", 'ILIKE', makeEscapeStr($request->$field) . '%');
            }
        }

        $fields = ['seikyu_sime_dt'];
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $query->where($this->table . ".{$field}", '=', $request->$field);
            }
        }

        if(!empty($request->sort)) {
            $query->orderBy($request->sort, $request->order);
        }
    }

    // 荷主マスタ
    public function scopeJoinMNinusi($query, $type = 'left', $joinAlias = 'm_ninusi', $first = 'ninusi_cd')
    {
        $query->join("m_ninusi AS {$joinAlias}", "{$this->table}.{$first}", '=', "{$joinAlias}.ninusi_cd", $type);
    }

    //売上データ（t_uriage）
    public function scopeJoinTUriage($query, $type = 'left', $joinAlias = 't_uriage')
    {
        $query->join("t_uriage AS {$joinAlias}", function ($j) use ($joinAlias) {
            $j->on("{$this->table}.seikyu_no", '=', "{$joinAlias}.seikyu_no");
            $j->on("{$this->table}.seikyu_sime_dt", '=', "{$joinAlias}.seikyu_sime_dt");
        }, null, null, $type);
    }
}
