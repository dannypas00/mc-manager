<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\SettingsInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\QueryBuilder;

final class ShowRepository
{
    /**
     * @codeCoverageIgnore It's just the vendor query builder
     * @param SettingsInterface $settings
     * @param int $id
     * @return Model
     */
    public function show(SettingsInterface $settings, int $id): Model
    {
        return QueryBuilder::for($settings->getModelName())
            ->allowedFilters($settings->getAllowedFilters())
            ->allowedFields($settings->getAllowedFields())
            ->allowedIncludes($settings->getAllowedIncludes())
            ->findOrFail($id);
    }
}
