<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JobApplication;
use App\Selection;

class SelectionApplicant extends Model
{
    protected $table = 'selection_applicant';
    protected $primaryKey = 'selection_applicant_id';
    protected $fillable = [
        'selection_id',
        'application_id',
        'batch_stage_id',
        'score',
        'notes',
        'status',
        'description',
    ];

    public function batchStage()
    {
        return $this->belongsTo(RecruitmentBatchStage::class, 'batch_stage_id', 'id');
    }

    public function jobapplication()
    {
        return $this->belongsTo(JobApplication::class, 'application_id', 'application_id');
    }   

    public function selection()
    {
        return $this->belongsTo(Selection::class, 'selection_id', 'selection_id');
    }

    public function aspectScores()
    {
        return $this->hasMany(SelectionApplicantScore::class, 'selection_applicant_id', 'selection_applicant_id');
    }
}
