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
        'roles_id',
        'is_role_verified',
        'email_verification_code'
    ];
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
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

    public function assignedVacancies()
    {
        return $this->belongsToMany(JobVacancie::class, 'job_vacancy_hr', 'user_id', 'vacancies_id');
    }

    public function isSuperAdmin()
    {
        if (!$this->role) return false;
        $name = str_replace(' ', '', strtolower($this->role->name));
        return strpos($name, 'superadmin') !== false;
    }
}
