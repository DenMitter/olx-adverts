<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Advertisement;

class ValidOlxUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $host = parse_url($value, PHP_URL_HOST);
        $path = parse_url($value, PHP_URL_PATH) ?? '';

        $hostOk = $host === 'olx.ua' || str_ends_with($host ?? '', '.olx.ua');
        $isAdPath = str_contains($path, '/d/') || str_ends_with($path, '.html');
        
        if(!$hostOk || !$isAdPath) {
            $fail('Invalid URL. Should be from olx.ua');
        }
    }
}
