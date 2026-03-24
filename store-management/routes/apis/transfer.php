<?php

use App\Http\Controllers\apis\TransferController;
use Illuminate\Support\Facades\Route;


 Route::get('/hold', [TransferController::class, 'indexHoldTransfers']);

 Route::get('/prepared', [TransferController::class, 'indexPreparedTransfers']);

 Route::get('/dropped', [TransferController::class, 'indexDroppedTransfers']);

 Route::post('/sync', [TransferController::class, 'sync']);

 Route::get('/summary', [TransferController::class, 'getSummary']);

 Route::post('/prepare/{id}', [TransferController::class, 'prepare']);

 Route::post('/drop/{id}', [TransferController::class, 'drop']);

 Route::get('/dropped-summary', [TransferController::class, 'droppedTransfersSummary']);