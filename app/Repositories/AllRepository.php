<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\SettingsInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\Exceptions\AllowedFieldsMustBeCalledBeforeAllowedIncludes;
use Spatie\QueryBuilder\QueryBuilder;

final class AllRepository
{
    /**
     * @codeCoverageIgnore It's just the vendor query builder
     */
    public function get(SettingsInterface $settings): Collection
    {
        return $this->setupQueryBuilder($settings)->get();
    }

    /**
     * @codeCoverageIgnore It's just the vendor query builder
     *
     * @throws AllowedFieldsMustBeCalledBeforeAllowedIncludes
     */
    private function setupQueryBuilder(SettingsInterface $settings): QueryBuilder
    {
        return QueryBuilder::for($settings->getModelName())
            ->allowedFilters($settings->getAllowedFilters())
            ->allowedFields($settings->getAllowedFields())
            ->allowedIncludes($settings->getAllowedIncludes())
            ->allowedSorts($settings->getAllowedSorts());
    }
}
