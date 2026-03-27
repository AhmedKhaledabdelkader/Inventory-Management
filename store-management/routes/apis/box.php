<?php

use App\Http\Controllers\apis\BoxController;
use Illuminate\Support\Facades\Route;



Route::post('/', [BoxController::class, 'create'])->middleware(['auth.user','validate.box']);

Route::get('/', [BoxController::class, 'index'])->middleware(['auth.user']);


