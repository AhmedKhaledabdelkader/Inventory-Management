<?php

use App\Http\Controllers\apis\TransferController;
use Illuminate\Support\Facades\Route;


 Route::get('/hold', [TransferController::class, 'indexHoldTransfers']);

 Route::get('/prepared', [TransferController::class, 'indexPreparedTransfers']);

 Route::get('/dropped', [TransferController::class, 'indexDroppedTransfers']);

 Route::post('/sync', [TransferController::class, 'sync']);

 Route::get('/summary', [TransferController::class, 'getSummary']);




 Route::get('/{id}', [TransferController::class, 'findTransfer']);

 Route::post('/prepare/{id}', [TransferController::class, 'prepare'])->middleware(['auth.user']);

 Route::post('/drop/{id}', [TransferController::class, 'drop']);

 Route::get('/dropped-summary', [TransferController::class, 'droppedTransfersSummary']);


Route::post('/verify/manual/{id}', [TransferController::class, 'verifyManual']);

Route::post('/reject/{id}', [TransferController::class, 'reject']);