<?php

namespace App\Http\Requests\Storage;

use Illuminate\Foundation\Http\FormRequest;

class StorageListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'path' => 'sometimes|string|nullable',
        ];
    }
}
