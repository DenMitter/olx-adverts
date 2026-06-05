<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'olx_id',
        'url',
        'last_price',
        'last_checked_at',
    ];

    protected function casts()
    {
        return [
            'last_checked_at' => 'datetime',
            'last_price' => 'decimal:2'
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public static function extractOlxId($url)
    {
        preg_match('#-ID([A-Za-z0-9]+)\.html#', parse_url($url, PHP_URL_PATH), $matches);
        return $matches[1] ?? '';
    }
}
