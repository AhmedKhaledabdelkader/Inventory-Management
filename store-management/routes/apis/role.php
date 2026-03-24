<?php


use App\Http\Controllers\apis\UserController;
use Illuminate\Support\Facades\Route;




Route::get('/', [UserController::class, 'getAllRoles']);