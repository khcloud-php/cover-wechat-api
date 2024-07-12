<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Auth\Authorizable;

class User extends Base implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['avatar', 'nickname', 'gender', 'setting'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'salt'
    ];

    protected $casts = [
        'setting' => 'json'
    ];

    /**
     * 头像路径处理
     *
     * @param string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if (!str_contains($value, 'http')) return env('STATIC_FILE_URL') . '/' . $value;
        return $value;
    }
}
