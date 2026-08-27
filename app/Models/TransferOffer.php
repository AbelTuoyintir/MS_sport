<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferOffer extends Model
{
    protected $fillable = [
        'listing_id',
        'player_id',
        'buying_team_id',
        'selling_team_id',
        'offer_amount',
        'proposed_contract_years',
        'expiry_date',
        'status',
        'notes',
        'deal_type',
        'parent_offer_id',
        'counter_amount',
        'counter_notes',
    ];

    public function listing()
    {
        return $this->belongsTo(TransferListing::class, 'listing_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function buyingTeam()
    {
        return $this->belongsTo(Team::class, 'buying_team_id');
    }

    public function sellingTeam()
    {
        return $this->belongsTo(Team::class, 'selling_team_id');
    }

    public function parentOffer()
    {
        return $this->belongsTo(TransferOffer::class, 'parent_offer_id');
    }

    public function counterOffers()
    {
        return $this->hasMany(TransferOffer::class, 'parent_offer_id');
    }
}
