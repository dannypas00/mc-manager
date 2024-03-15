<?php

declare(strict_types=1);

namespace App\Interfaces;

use Spatie\QueryBuilder\AllowedFilter;

interface SettingsInterface
{
    public function getModelName(): string;

    /**
     * @return string[]
     */
    public function getAllowedFields(): array;

    /**
     * @return string[]
     */
    public function getAllowedIncludes(): array;

    /**
     * @return array<AllowedFilter|string>
     */
    public function getAllowedFilters(): array;

    /**
     * @return string[]
     */
    public function getAllowedSorts(): array;
}
