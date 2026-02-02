<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JobVacancie;
use App\JobApplicant;

class JobApplication extends Model
{
    protected $table = 'job_applications';
    protected $primaryKey = 'application_id';
    protected $fillable = [
        'vacancies_id',
        'job_applicant_id',
        'status',
    ];

    public function jobVacancie()
    {
        return $this->belongsTo(JobVacancie::class, 'vacancies_id', 'vacancies_id');
    }

    public function jobApplicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id', 'job_applicant_id');
    }

    public function selectionApplicant()
    {
        return $this->hasMany(SelectionApplicant::class, 'application_id', 'application_id');
    }
}
