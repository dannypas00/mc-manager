<?php

Route::inertia('/', 'Home')->middleware('auth')->name('home');
