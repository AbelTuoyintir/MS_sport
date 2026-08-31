<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoutAgent extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'nationality',
        'experience_rating',
        'weekly_fee',
        'team_id',
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
