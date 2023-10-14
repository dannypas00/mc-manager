<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\SettingsInterface;
use Illuminate\Database\Eloquent\Collection;
use Log;
use Spatie\QueryBuilder\Exceptions\AllowedFieldsMustBeCalledBeforeAllowedIncludes;
use Spatie\QueryBuilder\QueryBuilder;

final class AllRepository
{
    /**
     * @codeCoverageIgnore It's just the vendor query builder
     * @param SettingsInterface $settings
     * @return Collection
     */
    public function get(SettingsInterface $settings): Collection
    {
        try {
            return $this->setupQueryBuilder($settings)->get();
        } catch (AllowedFieldsMustBeCalledBeforeAllowedIncludes) {
            Log::emergency('Some developer could not resist to not touch this code.');
            return new Collection();
        }
    }

    /**
     * @codeCoverageIgnore It's just the vendor query builder
     * @param SettingsInterface $settings
     * @throws AllowedFieldsMustBeCalledBeforeAllowedIncludes
     * @return QueryBuilder
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
