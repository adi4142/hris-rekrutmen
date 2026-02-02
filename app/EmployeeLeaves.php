<?php

namespace App;
use App\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaves extends Model
{
    protected $table = 'employees_leaves';
    protected $primaryKey = 'employees_leaves_id';
    protected $keyType = 'string';
    protected $fillable = [
        'nip',
        'start_date',
        'end_date',
        'reason',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip');
    }
}
