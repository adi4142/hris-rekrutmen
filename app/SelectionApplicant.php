<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JobApplication;
use App\Selection;

class SelectionApplicant extends Model
{
    protected $table = 'selection_applicants';
    protected $primaryKey = 'selection_applicant_id';
    protected $fillable = [
        'selection_id',
        'application_id',
        'score',
        'notes',
        'status',
    ];

    public function jobapplication()
    {
        return $this->belongsTo(JobApplication::class, 'application_id', 'application_id');
    }   

    public function selection()
    {
        return $this->belongsTo(Selection::class, 'selection_id', 'selection_id');
    }
}
