<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class MMenu extends BaseModel
{
    use HasFactory;

    protected $table = 'm_menu';

    protected $primaryKey = 'user_cd';
    public $incrementing = false;

    public $hidden = [
        'user_cd',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];

    const CREATED_AT = 'add_dt';
    const UPDATED_AT = 'upd_dt';

    protected $fillable = [
        'user_cd',
        'pgid1',
        'pgid2',
        'pgid3',
        'pgid4',
        'pgid5',
        'pgid6',
        'pgid7',
        'pgid8',
        'pgid9',
        'pgid10',
        'pgid11',
        'pgid12',
        'pgid13',
        'pgid14',
        'pgid15',
        'pgid16',
        'pgid17',
        'pgid18',
        'pgid19',
        'pgid20',
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
            $model->add_user_cd = Auth::id();
        });

        static::updating(function ($model) {
            $model->upd_user_cd = Auth::id();
        });
    }
}