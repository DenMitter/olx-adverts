<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidOlxUrl;

class StoreSubscriptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'url' => ['required', 'url:http,https', new ValidOlxUrl()]
        ];
    }
}
