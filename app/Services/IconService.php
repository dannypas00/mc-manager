<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Storage;
use Str;

class IconService
{
    /**
     * @return string File location
     */
    public function storeServerIcon(UploadedFile $file): string
    {
        $location = 'server_icons/' . Str::uuid() . '.' . $file->extension();
        $this->disk()->putFileAs($location, $file);

        return $location;
    }

    public function getPublicUrl(string $location): string
    {
        return $this->disk()->url($location);
    }

    private function disk()
    {
        return Storage::disk('profile-images');
    }
}
