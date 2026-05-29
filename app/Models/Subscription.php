<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
        'confirm_token',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
