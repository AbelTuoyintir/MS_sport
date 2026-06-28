<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
    protected $fillable = [
        'player_id',
        'type',
        'description',
        'started_at',
        'expected_return_at',
        'returned_at',
        'severity',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
