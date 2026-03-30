<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SelectionApplicant;

class Selection extends Model
{
    protected $table = 'selection';
    protected $primaryKey = 'selection_id';
    protected $fillable = [
        'name',
        'description',
    ];

    public function selectionApplicant()
    {
        return $this->belongsTo(SelectionApplicant::class, 'selection_applicant_id', 'selection_applicant_id');
    }

    public function aspects()
    {
        return $this->hasMany(SelectionAspect::class, 'selection_id', 'selection_id');
    }
}
