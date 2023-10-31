<?php

namespace App\Http\Requests\Storage;

use Illuminate\Foundation\Http\FormRequest;

class StorageContentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'path' => 'required|string',
        ];
    }
}
