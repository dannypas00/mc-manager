<?php

declare(strict_types=1);

namespace App\ModelSettings;

use App\Interfaces\SettingsInterface;
use App\Models\Server;

class ServerSettings implements SettingsInterface
{

    public function getModelName(): string
    {
        return Server::class;
    }

    public function getAllowedFields(): array
    {
        return [];
    }

    public function getAllowedIncludes(): array
    {
        return [
            'users',
        ];
    }

    public function getAllowedFilters(): array
    {
        return [];
    }

    public function getAllowedSorts(): array
    {
        return [];
    }
}
