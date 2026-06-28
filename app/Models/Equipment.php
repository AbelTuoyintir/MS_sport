<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'total_quantity',
        'available_quantity',
        'condition',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
