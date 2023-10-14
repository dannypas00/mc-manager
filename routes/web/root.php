<?php

Route::inertia('/', 'Home')->middleware('auth')->name('web.home');
