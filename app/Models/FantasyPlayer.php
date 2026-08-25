<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FantasyPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fantasy_team_id',
        'player_id',
        'is_captain',
        'is_starter',
    ];

    public function fantasyTeam()
    {
        return $this->belongsTo(FantasyTeam::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
