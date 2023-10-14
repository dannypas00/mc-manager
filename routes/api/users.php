<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->name('current')->get('/current', function (Request $request) {
    return $request->user();
});
