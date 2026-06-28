<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'attendable_type',
        'attendable_id',
        'eventable_type',
        'eventable_id',
        'status',
        'notes',
    ];

    public function attendable()
    {
        return $this->morphTo();
    }

    public function eventable()
    {
        return $this->morphTo();
    }
}
