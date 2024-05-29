<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class TNyukin extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_nyukin';

    protected $primaryKey = 'nyukin_no';

    protected $fillable = [
        'nyukin_no',
        'nyukin_dt',
        'ninusi_cd',
        'seikyu_sime_dt',
        'genkin_kin',
        'furikomi_kin',
        'furikomi_tesuryo_kin',
        'tegata_kin',
        'tegata_kijitu_kin',
        'sousai_kin',
        'nebiki_kin',
        'sonota_nyu_kin',
        'biko',
        'sime_kakutei_kbn',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];


   
    public $timestamps = true;
    public $incrementing = false;

    protected $appends = ['nyukin_gokei'];
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
        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'nyukin_dt',
                'nyukin_no',
            ];
            foreach ($fromTo as $field) {
                if (is_null($value)) continue;
                if ($key == "{$field}_from") $query->where("{$this->table}.{$field}", '>=', $value);
                if ($key == "{$field}_to") $query->where("{$this->table}.{$field}", '<=', $value);
            }
        }

        if($request->filled('bumon_cd_from')) {
            $query->where('m_ninusi.bumon_cd', '>=', $request->get('bumon_cd_from'));
        }

        if($request->filled('bumon_cd_to')) {
            $query->where('m_ninusi.bumon_cd', '<=', $request->get('bumon_cd_to'));
        }
        
        if(!empty($request->sort)) {
            $query->orderBy($request->sort, $request->order);
        }
    }

    public function scopeJoinMNinusi($query, $type = 'left', $joinAlias = 'm_ninusi')
    {
        $query->join("m_ninusi AS {$joinAlias}", $this->table . ".ninusi_cd", '=', "{$joinAlias}.ninusi_cd", $type);
    }
    
    public function getNyukinGokeiAttribute() {
        return $this->genkin_kin + $this->sousai_kin + $this->furikomi_kin 
        + $this->furikomi_tesuryo_kin + $this->sonota_nyu_kin + $this->tegata_kin;
    }


    public function scopeFilterList($query, $request)
    {
        if($request->filled('hed_nuikin_dt_from')) {
            $query->where('nyukin_dt', '>=', $request->hed_nuikin_dt_from);
        }
        if($request->filled('hed_nuikin_dt_to')) {
            $query->where('nyukin_dt', '<=', $request->hed_nuikin_dt_to);
        }
        if(!empty($request->sort)) {
            $query->orderBy($request->sort, $request->order);
        } else {
            $query->orderBy('nyukin_no', 'asc');
        }
    }
}
