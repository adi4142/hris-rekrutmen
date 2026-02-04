<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Role;
use App\JobApplicant;   

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'roles_id'
    ];
    protected $hidden = [
        'password',
    ];

    public function applicant()
    {
        return $this->hasOne(JobApplicant::class, 'user_id', 'user_id');
    }

    public function tamu()
    {
        return $this->hasOne(JobApplicant::class, 'user_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id', 'roles_id');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
