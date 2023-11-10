<?php

use App\Models\User;

Broadcast::channel('log-request', function (User $user) {
    dd(request()->all());
    $disk = Auth::user();
    $stream = $disk->readStream('logs/latest.log');
    $size = $disk->size('logs/latest.log');
    $offset = $size - 1000;
    fseek($stream, max($offset, 0));
    echo fread($stream, 1000);
    return Auth::check();
});
