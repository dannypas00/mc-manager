<?php

namespace App\Traits;

use Arr;

trait PadsArrayWithNull
{
    private function padArrayWithNull(array $keys, array $assocaitiveValues): array
    {
        return $assocaitiveValues + array_fill_keys($keys, null);
    }

    private function exceptValues(array $input, array $exceptValues): array {
        return array_flip(Arr::except($input, $exceptValues));
    }
}
