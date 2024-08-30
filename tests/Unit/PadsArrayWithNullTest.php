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

    public function test_that_it_excludes_values(): void
    {
        $associative = ['a', 'b', 'c', 'd'];
        $excludeValues = ['a', 'c'];
        $expect = ['b', 'd'];
        $actual = $this->exceptValues($associative, $excludeValues);
        assertEquals($expect, $actual);
    }

    public function test_it_excludes_non_fillable_values(): void
    {
        $keys = ['a', 'b', 'c'];
        $values = [
            'a' => 'testa',
            'c' => 'testc',
            'd' => 'testd',
        ];

        $padded = $this->getOnlyPaddedFillable($keys, $values, ['c']);

        self::assertArrayHasKey('a', $padded);
        self::assertArrayHasKey('b', $padded);
        self::assertNull($padded['b']);
        self::assertArrayNotHasKey('c', $padded);
        self::assertArrayNotHasKey('d', $padded);
    }
}
