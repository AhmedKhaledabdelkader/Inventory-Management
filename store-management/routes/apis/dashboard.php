<?php

use App\Http\Controllers\apis\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/quality-control/summary', [DashboardController::class, 'getQualityControlsummary'])->middleware(['auth.user']);

