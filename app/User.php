<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'sys_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'sys_users_name',
        'email',
        'password',
        'password_key',
        'password_expire_days',
        'mobile',
        'date_of_birth',
        'gender',
        'religion',
        'last_login',
        'status',
        'user_image',
        'address',
        'default_url',
        'default_module_id',
        'remember_token',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
