<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

class ServerRconCommandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'server_id' => 'required|integer|exists:servers,id',
            'command'   => 'required|string|min:1',
        ];
    }
}
