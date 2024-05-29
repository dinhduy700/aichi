<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;


class TSeikyuSimebiSiji extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_seikyu_simebi_siji';

    protected $primaryKey = ['seikyu_sime_dt'];

    protected $fillable = [
        'seikyu_sime_dt',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt',
    ];

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
        if ($request->filled('seikyu_sime_dt')) {
            $query->where('t_seikyu_simebi_siji.seikyu_sime_dt', '>=', $request->seikyu_sime_dt);
        }
    }

   
}