<?php

Route::as('users.')->prefix('users')->group(base_path('routes/webapi/users.php'));
Route::as('servers.')->prefix('servers')->group(base_path('routes/webapi/servers.php'));
