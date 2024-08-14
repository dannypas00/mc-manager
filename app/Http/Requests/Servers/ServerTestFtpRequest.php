<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

class ServerTestFtpRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore It's just validation
     */
    public function rules(): array
    {
        return [
            'host'     => 'string|required',
            'user'     => 'string|required',
            'port'     => 'int|required',
            'password' => 'string|required',
            'is_sftp'  => 'boolean|required'
        ];
    }
}
