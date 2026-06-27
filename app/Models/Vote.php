<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'player_id',
        'ip_address',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
