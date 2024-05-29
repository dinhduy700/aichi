<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MMeisyo extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 'm_meisyo';

    protected $primaryKey = ['meisyo_kbn', 'meisyo_cd'];

    protected $fillable = [
        'meisyo_kbn',
        'meisyo_cd',
        'kana',
        'meisyo_nm',
        'jyuryo_kansan',
        'sekisai_kbn',
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
        if ($request->filled('meisyo_cd')) {
            $query->where('meisyo_cd', 'ilike', makeEscapeStr($request->meisyo_cd).'%');
        }

        if ($request->filled('meisyo_kbn')) {
            $query->where('meisyo_kbn', 'ilike', makeEscapeStr($request->meisyo_kbn).'%');
        }

        if ($request->filled('meisyo_kana')) {
            $query->where('kana', 'ilike', makeEscapeStr($request->meisyo_kana).'%');
        }

        if($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'kyumin_flg');
        }

        if(!empty($request->sort)) {
            $query->orderBy($request->sort, $request->order);
        } else {
            $query->orderBy('meisyo_kbn', 'asc');
            $query->orderBy('meisyo_cd', 'asc');
        }
    }

    public function scopeKbn($query, $kbn, $cdAlias = "meisyo_cd", $nmAlias = "meisyo_nm")
    {
        $query->where('meisyo_kbn', $kbn)
            ->addSelect("meisyo_cd AS {$cdAlias}")
            ->addSelect("meisyo_nm AS {$nmAlias}");
    }
}
