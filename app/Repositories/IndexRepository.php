<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\IndexRepositoryInterface;
use App\Interfaces\SettingsInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\QueryBuilder\QueryBuilder;

class IndexRepository
{
    /**
     * @param SettingsInterface $settings
     * @return LengthAwarePaginator
     */
    public function getPaginator(SettingsInterface $settings): LengthAwarePaginator
    {
        return QueryBuilder::for($settings->getModelName())
            ->allowedIncludes($settings->getAllowedIncludes())
            ->allowedFilters($settings->getAllowedFilters())
            ->allowedFields($settings->getAllowedFields())
            ->allowedSorts($settings->getAllowedSorts())
            ->paginate(request('per_page'))
            ->appends(request()?->query());
    }
}
