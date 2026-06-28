<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoutReport extends Model
{
    protected $fillable = [
        'team_id',
        'player_name',
        'current_club',
        'position',
        'age',
        'rating',
        'strengths',
        'weaknesses',
        'summary',
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
