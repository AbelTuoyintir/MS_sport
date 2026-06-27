<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'reference_code',
        'team_name',
        'team_size',
        'division',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_path',
        'password',
        'registration_status',
        'submitted_at',
    ];

    public function owners()
    {
        return $this->hasMany(Owner::class);
    }

    public function primaryOwner()
    {
        return $this->hasOne(Owner::class)->where('is_primary', true);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public static function generateReferenceCode()
    {
        do {
            $reference = 'APX-' . now()->format('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (self::where('reference_code', $reference)->exists());

        return $reference;
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value) && password_get_info($value)['algo'] === null) {
            $this->attributes['password'] = bcrypt($value);
            return;
        }

        $this->attributes['password'] = $value;
    }
}
