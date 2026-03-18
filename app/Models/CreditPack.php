<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPack extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'credits_amount',
        'price_amount',
        'currency',
        'description',
        'is_active',
        'sort_order',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credits_amount' => 'integer',
        'price_amount' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];
}
