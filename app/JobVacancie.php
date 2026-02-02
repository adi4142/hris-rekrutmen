<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Departement;
use App\Position;

class JobVacancie extends Model
{
    protected $table = 'job_vacancies';
    protected $primaryKey = 'vacancies_id';
    protected $fillable = [
        'title',
        'departement_id',
        'position_id',
        'description',
        'requirements',
        'status',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'departement_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }
}
