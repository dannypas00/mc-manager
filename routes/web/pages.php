<?php

declare(strict_types=1);

Route::inertia('/', 'Home/HomePage')->name('home');
Route::inertia('profile', 'Users/ProfileEdit')->name('me.profile');
Route::inertia('settings', 'Users/UserSettings')->name('me.settings');
