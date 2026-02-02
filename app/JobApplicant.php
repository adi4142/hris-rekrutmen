<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JobApplication;
use App\SelectionApplicant;

class JobApplicant extends Model
{
    protected $table = 'job_applicants';
    protected $primaryKey = 'job_applicant_id';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'cv_file'
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_applicant_id', 'job_applicant_id');
    }

    public function selectionApplicant()
    {
        return $this->hasMany(SelectionApplicant::class, 'job_applicant_id', 'job_applicant_id');
    }
}

