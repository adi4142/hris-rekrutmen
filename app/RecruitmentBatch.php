<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecruitmentBatch extends Model
{
    protected $table = 'recruitment_batches';
    protected $fillable = ['vacancies_id', 'name', 'quota', 'date', 'status', 'description'];

    public function vacancy()
    {
        return $this->belongsTo(JobVacancie::class, 'vacancies_id', 'vacancies_id');
    }

    public function stages()
    {
        return $this->hasMany(RecruitmentBatchStage::class, 'batch_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'batch_id', 'id');
    }

    /**
     * Get status automatically based on dates
     */
    public function getComputedStatusAttribute()
    {
        // If manually closed, respect that
        if ($this->status === 'closed') {
            return 'closed';
        }

        $today = \Carbon\Carbon::today();
        
        // Determine start and end date
        if ($this->stages->isEmpty()) {
            $startDate = \Carbon\Carbon::parse($this->date);
            $endDate = $startDate;
        } else {
            $dates = $this->stages->pluck('date')->filter()->map(function($d) {
                return \Carbon\Carbon::parse($d);
            })->sort();
            $startDate = $dates->first();
            $endDate = $dates->last();
        }

        // 1. If today is past the end date, it's CLOSED
        if ($today->gt($endDate)) {
            return 'closed';
        }

        // 2. If today is within the range (start to end inclusive), it's ACTIVE
        if ($today->between($startDate, $endDate)) {
            return 'active';
        }

        // 3. If today is before the start date:
        //    - It's DRAFT if the manual status is 'draft'
        //    - It's ACTIVE if the manual status is 'active' (meaning scheduled/ready)
        if ($today->lt($startDate)) {
            return $this->status === 'draft' ? 'draft' : 'active';
        }

        return $this->status;
    }
}
