<?php


use Illuminate\Support\Facades\Route;





Route::prefix('users')->group(base_path('routes/apis/user.php'));


Route::prefix('roles')->group(base_path('routes/apis/role.php'));


Route::prefix('transfers')->group(base_path('routes/apis/transfer.php'));