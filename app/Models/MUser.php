<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class MUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'm_user';

    protected $primaryKey = 'user_cd';
    public $incrementing = false;

    const CREATED_AT = 'add_dt';
    const UPDATED_AT = 'upd_dt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_cd',
        'passwd',
        'group',
        'biko',
        'kyumin_flg',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passwd',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'passwd' => 'hashed',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->passwd;
    }

    public function hasGroup($group)
    {
        return $this->group === $group;
    }

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
        if ($request->filled('user_cd')) {
            $query->where('user_cd',  'ilike', makeEscapeStr($request->user_cd) . '%');
        }

        if (!empty($request->group)) {
            $query->where('group', $request->group);
        }
        if ($request->has('kyumin_flg')) {
            setKyuminFlagFilter($query, $request->kyumin_flg, 'kyumin_flg');
        }
    }
}
