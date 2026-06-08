<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'olx_id',
        'url',
        'title',
        'last_price',
        'currency',
        'last_checked_at',
    ];

    protected function casts()
    {
        return [
            'olx_id' => 'integer',
            'last_checked_at' => 'datetime',
            'last_price' => 'decimal:2',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
