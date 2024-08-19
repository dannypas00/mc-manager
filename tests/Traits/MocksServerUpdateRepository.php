<?php

namespace Tests\Traits;

use App\Repositories\Servers\ServerUpdateRepository;
use Mockery\MockInterface;

trait MocksServerUpdateRepository
{
    public function mockServerUpdateRepositoryUpdateByPing(bool $returns = true): void
    {
        $this->mock(
            ServerUpdateRepository::class,
            fn (MockInterface $mock) => $mock
                ->expects('updateByPing')
                ->andReturn($returns)
        )->makePartial();
    }

    public function mockServerUpdateRepositoryUpdate(bool $returns = true, ?array $expectedData = null): void
    {
        $this->mock(
            ServerUpdateRepository::class,
            function (MockInterface $mock) use ($expectedData, $returns) {
                $expectation = $mock->expects('update');

                if ($expectedData) {
                    $expectation->withSomeOfArgs($expectedData);
                }

                return $expectation->andReturn($returns);
            }
        )->makePartial();
    }
}
