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
        'order',
    ];

    public function selectionApplicant()
    {
        return $this->belongsTo(SelectionApplicant::class, 'selection_applicant_id', 'selection_applicant_id');
    }
}
