<?php

namespace App\Http\Requests\Storage;

use Illuminate\Foundation\Http\FormRequest;

class StorageContentRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore we trust that returning an array works
     */
    public function rules(): array
    {
        return [
            'path' => 'required|string',
        ];
    }
}
