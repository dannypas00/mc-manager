<?php

namespace App\Settings;

use App\Filters\FilterDateRange;
use App\Interfaces\SettingsInterface;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

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
        return [];
    }

    public function getAllowedFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::partial('email'),
            AllowedFilter::custom('created_at', new FilterDateRange()),
        ];
    }

    public function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('name'),
        ];
    }
}
