<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Departement;
use App\Position;

class JobVacancie extends Model
{
    use SoftDeletes;
    
    protected $table = 'job_vacancies';
    protected $primaryKey = 'vacancies_id';
    protected $fillable = [
        'title',
        'departement_id',
        'departement_id',
        'position_id',
        'description',
        'expired_at',
        'requirements',
        'required_documents',
        'status',
        'job_type',
        'salary_type',
        'salary_nominal',
        'quota',
    ];

    public function batches()
    {
        return $this->hasMany(RecruitmentBatch::class, 'vacancies_id', 'vacancies_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'vacancies_id', 'vacancies_id');
    }

    protected $casts = [
        'required_documents' => 'array',
        'requirements' => 'array',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'departement_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }



    public function hrs()
    {
        return $this->belongsToMany(User::class, 'job_vacancy_hr', 'vacancies_id', 'user_id');
    }
}
