<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecruitmentBatchStage extends Model
{
    protected $table = 'recruitment_batch_stages';
    protected $fillable = ['batch_id', 'selection_id', 'date', 'start_time', 'end_time', 'location', 'description', 'room_url'];

    public function batch()
    {
        return $this->belongsTo(RecruitmentBatch::class, 'batch_id', 'id');
    }

    public function selection()
    {
        return $this->belongsTo(Selection::class, 'selection_id', 'selection_id');
    }
}
