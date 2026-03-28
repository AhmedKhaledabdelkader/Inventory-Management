<?php

use App\Http\Controllers\apis\RunnerController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [RunnerController::class, 'dashboard'])->middleware(['auth.user']);

Route::get('/lots', [RunnerController::class, 'myLots'])->middleware(['auth.user']);

Route::post('/pickup', [RunnerController::class, 'pickup'])->middleware(['auth.user','validate.lotCode']);

Route::post('/lots/status/{id}', [RunnerController::class, 'updateStatus'])->middleware(['auth.user','validate.manualStatus']);

Route::post('/lots/deliver/{id}', [RunnerController::class, 'deliver'])->middleware(['auth.user','validate.runnerDelivery']);