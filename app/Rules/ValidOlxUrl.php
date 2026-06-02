<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidOlxUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $host = parse_url($value, PHP_URL_HOST);
        $path = parse_url($value, PHP_URL_PATH);

        $hostOk = $host === 'olx.ua' || str_ends_with($host ?? '', '.olx.ua');
        $hasId = preg_match('#-ID([A-Za-z0-9]+)\.html#', $path ?? '');
        
        if(!$hostOk || !$hasId) {
            $fail('Invalid URL. Should be from olx.ua');
        }
    }
}
