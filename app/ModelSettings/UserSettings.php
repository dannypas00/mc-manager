<?php

declare(strict_types=1);

namespace App\ModelSettings;

use App\Interfaces\SettingsInterface;
use App\Models\User;

/**
 * @codeCoverageIgnore useless to test settings files
 */
class UserSettings implements SettingsInterface
{
    public function getModelName(): string
    {
        return User::class;
    }

    public function getAllowedFields(): array
    {
        return [];
    }

    public function getAllowedIncludes(): array
    {
        return [
            'servers',
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
