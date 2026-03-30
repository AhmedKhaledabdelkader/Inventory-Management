<?php


use App\Http\Controllers\apis\UserController;
use Illuminate\Support\Facades\Route;




Route::post('/', [UserController::class, 'store'])->middleware(["validate.user"])->middleware(["auth.user","role:Admin"]);

Route::patch('/toggle-status/{userId}', [UserController::class, 'toggleStatus'])->middleware(["auth.user","role:Admin"]);

Route::put('/{userId}', [UserController::class, 'update'])->middleware(["validate.update.user"])->middleware(["auth.user","role:Admin"]);

Route::delete('/{userId}', [UserController::class, 'destroy'])->middleware(["auth.user","role:Admin"]);

Route::get('/statistics', [UserController::class, 'getUserStatistics'])->middleware(["auth.user","role:Admin"]);

Route::get('/', [UserController::class, 'search'])->middleware(["auth.user","role:Admin"]);

Route::post('/login', [UserController::class, 'login']);


Route::post('/logout', [UserController::class, 'logout'])->middleware(['auth.user']);

Route::post('/logoutAll', [UserController::class, 'logoutAll'])->middleware(['auth.user']);

Route::post('/change-password', [UserController::class, 'changePassword'])->middleware(['auth.user','validate.password']);