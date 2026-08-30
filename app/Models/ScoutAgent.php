<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'experience_rating',
        'specialization',
        'weekly_fee',
        'is_active',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'scout_agent_team')->withTimestamps()->withPivot('signed_at');
    }
}
