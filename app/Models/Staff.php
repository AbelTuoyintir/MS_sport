<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'role',
        'nationality',
        'joined_at',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function contract()
    {
        return $this->morphOne(Contract::class, 'contractable');
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }
}
