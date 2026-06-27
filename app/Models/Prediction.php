<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'game_id',
        'user_name',
        'home_score_prediction',
        'away_score_prediction',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
