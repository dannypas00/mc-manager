<?php

Route::inertia('login', 'Auth/Login')
    ->middleware('guest')
    ->name('login');
