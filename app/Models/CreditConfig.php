<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditConfig extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expiration_months',
        'cancel_grace_minutes',
        'penalty_mode',
        'penalty_value',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expiration_months' => 'integer',
        'cancel_grace_minutes' => 'integer',
        'penalty_value' => 'integer',
        'is_active' => 'boolean',
    ];
}
