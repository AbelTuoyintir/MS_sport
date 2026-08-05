<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerRating extends Model
{
    protected $fillable = ['player_id', 'game_id', 'rating', 'session_id'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
