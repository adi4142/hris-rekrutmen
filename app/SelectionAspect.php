<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Selection;

class SelectionAspect extends Model
{
    protected $table = 'selection_aspects';
    protected $primaryKey = 'aspect_id';
    
    protected $fillable = [
        'selection_id',
        'name',
        'description'
    ];

    public function selection()
    {
        return $this->belongsTo(Selection::class, 'selection_id', 'selection_id');
    }
}
