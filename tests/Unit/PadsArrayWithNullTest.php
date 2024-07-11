<?php

namespace Tests\Unit;

use App\Traits\PadsArrayWithNull;

use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class PadsArrayWithNullTest extends TestCase
{
    use PadsArrayWithNull;

    public function test_that_it_pads_array(): void
    {
        $keys = ['a', 'b', 'c'];
        $values = [
            'a' => 'testa',
            'c' => 'testc',
        ];
        $padded = $this->padArrayWithNull($keys, $values);
        assertEquals(['a' => 'testa', 'b' => null, 'c' => 'testc'], $padded);
    }
}
