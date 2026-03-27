<?php

use App\Http\Controllers\apis\RunnerManagerController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', [RunnerManagerController::class, 'getDashboard'])->middleware(['auth.user']);

Route::get('/lots', [RunnerManagerController::class, 'index'])->middleware(['auth.user']);

Route::post('/lots/assign/{id}', [RunnerManagerController::class, 'assign'])->middleware(['auth.user','validate.runner.id']);

Route::get('/runners', [RunnerManagerController::class, 'indexAllRunnersWithAssignedLots'])->middleware(['auth.user']);