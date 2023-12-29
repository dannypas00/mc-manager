<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageDeleteRequest extends FormRequest
{
    /**
     * @codeCoverageIgnore we trust that returning an array works
     */
    public function rules(): array
    {
        return [
            'paths'   => 'required|array',
            // Not allowed to send empty path (as this would signify deleting the root)
            'paths.*' => 'string|regex:/^.+$/'
        ];
    }
}
