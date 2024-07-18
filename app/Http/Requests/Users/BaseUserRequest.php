<?php

namespace App\Http\Requests\Users;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Route;

class BaseUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string',
            'email'    => 'required|email',
            // On non-production environments, password can be any string
            'password' => app()->environment('production')
                ? Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->letters()
                    ->uncompromised()
                    ->sometimes()
                : 'string|sometimes',
        ];
    }
}
