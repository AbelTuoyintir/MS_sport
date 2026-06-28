<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferListing extends Model
{
    protected $fillable = [
        'player_id',
        'team_id',
        'asking_price',
        'reason',
        'type',
        'status',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function offers()
    {
        return $this->hasMany(TransferOffer::class, 'listing_id');
    }
}
