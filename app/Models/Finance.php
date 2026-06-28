<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = [
        'team_id',
        'type',
        'category',
        'amount',
        'date',
        'description',
        'reference',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
