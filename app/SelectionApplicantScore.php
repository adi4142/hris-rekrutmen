<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SelectionApplicant;
use App\SelectionAspect;

class SelectionApplicantScore extends Model
{
    protected $table = 'selection_applicant_scores';
    protected $primaryKey = 'score_id';
    
    protected $fillable = [
        'selection_applicant_id',
        'aspect_id',
        'score'
    ];

    public function selectionApplicant()
    {
        return $this->belongsTo(SelectionApplicant::class, 'selection_applicant_id', 'selection_applicant_id');
    }

    public function aspect()
    {
        return $this->belongsTo(SelectionAspect::class, 'aspect_id', 'aspect_id');
    }
}
