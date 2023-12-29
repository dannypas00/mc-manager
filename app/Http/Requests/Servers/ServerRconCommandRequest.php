<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

class ServerRconCommandRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore we trust that returning an array works
     */
    public function rules(): array
    {
        return [
            'command' => 'required|string|min:1',
        ];
    }
}
