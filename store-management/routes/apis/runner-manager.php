<?php

use App\Http\Controllers\apis\RunnerManagerController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', [RunnerManagerController::class, 'getDashboard'])->middleware(['auth.user','role:Runner Manager']);

Route::get('/lots', [RunnerManagerController::class, 'index'])->middleware(['auth.user','role:Runner Manager']);

Route::post('/lots/assign/{id}', [RunnerManagerController::class, 'assign'])->middleware(['auth.user',
'role:Runner Manager','validate.runner.id']);

Route::get('/runners', [RunnerManagerController::class, 'indexAllRunnersWithAssignedLots'])->middleware(['auth.user','role:Runner Manager']);

Route::get('/runners/all', [RunnerManagerController::class, 'indexAllRunners'])->middleware(['auth.user']);