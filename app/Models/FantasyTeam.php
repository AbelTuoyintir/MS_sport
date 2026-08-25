<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FantasyTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'budget_remaining',
        'total_points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fantasyPlayers()
    {
        return $this->hasMany(FantasyPlayer::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'fantasy_players')
                    ->withPivot('is_captain', 'is_starter')
                    ->withTimestamps();
    }
}
