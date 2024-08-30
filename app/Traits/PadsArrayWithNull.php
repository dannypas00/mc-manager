<?php

namespace App\Traits;

use Arr;

trait PadsArrayWithNull
{
    private function padArrayWithNull(array $keys, array $assocaitiveValues): array
    {
        return $assocaitiveValues + array_fill_keys($keys, null);
    }

    private function exceptKeys(array $input, array $exceptValues): array
    {
        return array_keys(Arr::except(array_flip($input), $exceptValues));
    }

    private function getOnlyPaddedFillable(array $fillable, array $data, array $hidden = []): array
    {
        $keys = $this->exceptKeys($fillable, $hidden);
        return $this->padArrayWithNull($keys, Arr::only($data, $keys));
    }
}
