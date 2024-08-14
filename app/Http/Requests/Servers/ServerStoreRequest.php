<?php

namespace App\Http\Requests\Servers;

use App\Enums\ServerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServerStoreRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore It's just validation
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'type' => ['required', Rule::enum(ServerType::class)],
        ];
    }
}
