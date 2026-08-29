<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'experience_rating',
        'weekly_fee',
        'nationality',
        'status',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
