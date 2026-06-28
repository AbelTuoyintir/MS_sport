<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'contractable_type',
        'contractable_id',
        'start_date',
        'end_date',
        'salary',
        'currency',
        'terms',
        'status',
    ];

    public function contractable()
    {
        return $this->morphTo();
    }
}
