<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

class ServerLogStreamRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore It's just validation
     */
    public function rules(): array
    {
        return [
            'start' => 'sometimes|integer|min:0'
        ];
    }
}
