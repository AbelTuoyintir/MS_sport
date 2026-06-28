<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    protected $fillable = [
        'team_id',
        'scheduled_at',
        'location',
        'focus',
        'plan',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'eventable');
    }
}
