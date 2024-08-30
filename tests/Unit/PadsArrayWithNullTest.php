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
        $actual = $this->exceptKeys($associative, $excludeValues);
        assertEquals($expect, $actual);
    }

    public function test_it_excludes_non_fillable_values(): void
    {
        $keys = ['a', 'b', 'c', 'g', 'h'];
        $values = [
            'a' => 'testa',
            'c' => 'testc',
            'd' => 'testd',
            'e' => 'teste',
            'f' => 'testf',
            'h' => 'testh',
        ];
        $except = ['c', 'e', 'f'];
        $unset = ['g', 'h'];

        $padded = $this->getOnlyPaddedFillable($keys, $values, $except, $unset);

        self::assertArrayHasKey('a', $padded);
        self::assertArrayHasKey('b', $padded);
        self::assertNull($padded['b']);
        self::assertArrayNotHasKey('c', $padded);
        self::assertArrayNotHasKey('d', $padded);
        self::assertArrayNotHasKey('e', $padded);
        self::assertArrayNotHasKey('f', $padded);
        self::assertArrayNotHasKey('g', $padded);
        self::assertArrayHasKey('h', $padded);
    }
}
