<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\JobVacancie;
use App\JobApplicant;

class JobApplication extends Model
{
    use SoftDeletes;
    
    protected $table = 'job_applications';
    protected $primaryKey = 'application_id';
    protected $fillable = [
        'vacancies_id',
        'job_applicant_id',
        'status',
        'documents',
        'batch_id',
        'offering_job_desc',
        'offering_salary',
        'offering_start_date',
        'offering_working_hours',
        'offering_leave_quota',
        'offering_accepted_at',
        'offering_rejected_at',
        'expected_salary',
        'negotiation_reason',
        'hr_negotiation_note',
        'offering_letter_no',
        'offering_letter_file',
        'offering_status',
    ];

    public function generateOfferingLetterNo()
    {
        $year = date('Y');
        $monthRoman = $this->getRomanNumeral(date('n'));
        $count = self::whereYear('created_at', $year)->whereNotNull('offering_letter_no')->count() + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "OFF/HR/{$monthRoman}/{$year}/{$sequence}";
    }

    private function getRomanNumeral($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$month] ?? 'I';
    }

    public function batch()
    {
        return $this->belongsTo(RecruitmentBatch::class, 'batch_id', 'id');
    }

    protected $casts = [
        'documents' => 'array',
    ];

    
    public function jobVacancie()
    {
        return $this->belongsTo(JobVacancie::class, 'vacancies_id', 'vacancies_id');
    }

    public function jobApplicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id', 'job_applicant_id');
    }

    public function selectionApplicant()
    {
        return $this->hasMany(SelectionApplicant::class, 'application_id', 'application_id');
    }

    /**
     * Check if all stages in the assigned batch have been scored
     */
    public function isSelectionCompleted()
    {
        if (!$this->batch_id) return false;
        
        $totalStages = $this->batch->stages->count();
        if ($totalStages === 0) return true;

        $completedCount = $this->selectionApplicant()
            ->where('status', '!=', 'unprocess')
            ->count();

        return $completedCount >= $totalStages;
    }

    /**
     * Get the next pending stage date if any
     */
    public function getNextStageInfo()
    {
        if (!$this->batch_id) return null;

        $completedStageIds = $this->selectionApplicant()
            ->where('status', '!=', 'unprocess')
            ->pluck('selection_id')
            ->toArray();

        $nextStage = $this->batch->stages()
            ->whereNotIn('selection_id', $completedStageIds)
            ->orderBy('date', 'asc')
            ->first();

        return $nextStage;
    }
    /**
     * Get Selection Progress Stats
     */
    public function getSelectionProgressAttribute()
    {
        if (!$this->batch_id) return [
            'total' => 0,
            'completed' => 0,
            'percent' => 0,
            'is_finished' => false,
            'next_stage' => null
        ];

        $totalStages = $this->batch->stages->count();
        $completedCount = $this->selectionApplicant()
            ->where('status', '!=', 'unprocess')
            ->count();
            
        return [
            'total' => $totalStages,
            'completed' => $completedCount,
            'percent' => $totalStages > 0 ? ($completedCount / $totalStages) * 100 : 0,
            'is_finished' => ($completedCount >= $totalStages),
            'next_stage' => $this->getNextStageInfo()
        ];
    }

    /**
     * Get Status Badge Info
     */
    public function getBadgeStatusAttribute()
    {
        $status = $this->status;
        $badge = 'secondary';
        $label = strtoupper($status);

        switch ($status) {
            case 'accepted': $badge = 'success'; break;
            case 'hired': $badge = 'success'; $label = 'HIRED'; break;
            case 'rejected': $badge = 'danger'; break;
            case 'process': $badge = 'warning'; break;
            case 'offering': $badge = 'primary'; break;
            case 'offering_sent': $badge = 'info'; $label = 'OFFERING SENT'; break;
            case 'negotiation_requested': $badge = 'orange'; $label = 'NEGOTIATION'; break;
            case 'pending':
            case 'applied': $badge = 'info'; break;
        }

        return [
            'badge' => $badge,
            'label' => $label,
            'style' => ($status == 'negotiation_requested') ? 'background-color: #fd7e14' : ''
        ];
    }
}
