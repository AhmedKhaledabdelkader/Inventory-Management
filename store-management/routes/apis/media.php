<?php

use App\Http\Controllers\Apis\MediaController;
use Illuminate\Support\Facades\Route;



Route::get('/{filename}', [MediaController::class, 'show'])
    ->where('filename', '.*');


