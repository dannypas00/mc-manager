<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class IconService
{
    /**
     * @return string File location
     */
    public function storeServerIcon(UploadedFile $file): string
    {
        $location = 'server_icons/' . Str::uuid() . '.png';
        $stream = Image::read($file)
            ->resize(64, 64)
            ->toPng()
            ->toFilePointer();
        $this->disk()->writeStream($location, $stream);

        return $location;
    }

    public function getPublicUrl(string $location): string
    {
        return $this->disk()->url($location);
    }

    public function deleteIcon(string $location): bool
    {
        return $this->disk()->delete($location);
    }

    /**
     * @param string $location
     * @return string
     */
    public function getIcon(string $location): string
    {
        return $this->disk()->get($location);
    }

    private function disk()
    {
        return Storage::disk('profile-images');
    }
}
