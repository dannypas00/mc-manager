<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageWriteRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore we trust that returning an array works
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }
}
