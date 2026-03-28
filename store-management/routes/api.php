<?php


use Illuminate\Support\Facades\Route;





Route::prefix('users')->group(base_path('routes/apis/user.php'));


Route::prefix('roles')->group(base_path('routes/apis/role.php'));


Route::prefix('transfers')->group(base_path('routes/apis/transfer.php'));


Route::prefix('transfers_issues')->group(base_path('routes/apis/transferIssue.php'));


Route::prefix('notifications')->group(base_path('routes/apis/notification.php'));


Route::prefix('boxes')->group(base_path('routes/apis/box.php'));

Route::prefix('lots')->group(base_path('routes/apis/lot.php'));


Route::prefix('runner-manager')->group(base_path('routes/apis/runner-manager.php'));


Route::prefix('runners')->group(base_path('routes/apis/runner.php'));


Route::prefix('media')->group(base_path('routes/apis/media.php'));
