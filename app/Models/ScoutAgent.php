<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoutAgent extends Model
{
    protected $fillable = [
        'name',
        'experience_rating',
        'specialization',
        'weekly_fee',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
