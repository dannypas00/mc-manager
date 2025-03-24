<?php

declare(strict_types=1);
use App\Models\Server;
use App\Models\User;

it('has many servers', function (): void {
    $user = User::factory()->create();

    Server::factory(3)->create([
        'user_id' => $user->id,
    ]);

    expect($user->servers)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Server::class);
});

it('broadcasts model updates', function (): void {
    $user = User::factory()->create();

    Event::fake();

    $user->name = 'Test User';
    $user->save();

    Event::assertDispatched(
        'eloquent.updated: ' . User::class,
        fn (string $event, User $model) => expect($model->id)->toEqual($user->id)
    );
});
