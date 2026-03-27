<?php

use App\Http\Controllers\apis\LotController;
use Illuminate\Support\Facades\Route;




Route::get('/available-boxes', [LotController::class, 'indexAvaliableBoxes'])->middleware(['auth.user']);

Route::post('/', [LotController::class, 'store'])->middleware(['auth.user','validate.lot']);

Route::get('/', [LotController::class, 'index'])->middleware(['auth.user']);


