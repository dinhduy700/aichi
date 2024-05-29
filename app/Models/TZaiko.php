<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class TZaiko extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_zaiko';

    protected $primaryKey = 'seq_no';

    protected $fillable = [
        'seq_no',
        'bumon_cd',
        'ninusi_cd',
        'hinmei_cd',
        'soko_cd',
        'location',
        'lot1',
        'lot2',
        'lot3',
        'case_su',
        'hasu',
        'su',
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
    // 部門マスタ
    public function scopeJoinMBumon($query, $type = 'left', $joinAlias = 'm_bumon')
    {
        $query->join("m_bumon AS {$joinAlias}", $this->table . ".bumon_cd", '=', "{$joinAlias}.bumon_cd", $type);
    }

    // 倉庫マスタ
    public function scopeJoinMSoko($query, $type = 'left', $joinAlias = 'm_soko')
    {
        $query->join("m_soko AS {$joinAlias}", function ($j) use ($joinAlias) {
            $j->on($this->table . ".soko_cd", '=', "{$joinAlias}.soko_cd");
            $j->on($this->table . ".bumon_cd", '=', "{$joinAlias}.bumon_cd");
        }, null, null, $type);
    }

    // 荷主マスタ
    public function scopeJoinMNinusi($query, $type = 'left', $joinAlias = 'm_ninusi')
    {
        $query->join("m_ninusi AS {$joinAlias}", "{$this->table}.ninusi_cd", '=', "{$joinAlias}.ninusi_cd", $type);
    }

    // 倉庫商品マスタ
    public function scopeJoinMSokoHinmei($query, $type = 'left', $joinAlias = 'm_soko_hinmei')
    {
        $query->join("m_soko_hinmei AS {$joinAlias}", function ($j) use ($joinAlias) {
            $j->on("{$this->table}.ninusi_cd", '=', "{$joinAlias}.ninusi_cd");
            $j->on("{$this->table}.hinmei_cd", '=', "{$joinAlias}.hinmei_cd");
        }, null, null, $type);
    }
}
