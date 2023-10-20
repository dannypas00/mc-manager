<?php

declare(strict_types=1);

use App\Models\Server;
use Inertia\Inertia;

Route::get('{id}', static fn (int $id) => Inertia::render(
    'Servers/ServerShowPage',
    ['server' => Server::findOrFail($id)]
))->name('show');
