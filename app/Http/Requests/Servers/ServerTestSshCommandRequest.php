<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

class ServerTestSshCommandRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore It's just validation
     */
    public function rules(): array
    {
        return [
            'host'        => 'string|required',
            'user'        => 'string|required',
            'port'        => 'int|required',
            'private_key' => 'string|required'
        ];
    }
}
