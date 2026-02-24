<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'user_id',
        'activity',
        'module',
        'details',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public static function log($activity, $module, $details = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'module' => $module,
            'details' => $details,
            'ip_address' => request()->ip()
        ]);
    }
}
