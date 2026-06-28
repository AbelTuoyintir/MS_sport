<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'position',
        'age',
        'nationality',
        'number',
        'goals',
        'assists',
        'rating',
        'status',
        'appearances',
        'minutes_played',
        'yellow_cards',
        'red_cards',
        'clean_sheets',
        'saves',
        'penalties_scored',
        'penalties_missed',
        'motm_awards',
        'photo_path',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
