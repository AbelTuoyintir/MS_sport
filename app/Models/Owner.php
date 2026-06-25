<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    //

    protected $fillable = [
        'team_id',
        'full_name',
        'ownership_percentage',
        'email',
        'phone',
        'is_primary',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    
}
