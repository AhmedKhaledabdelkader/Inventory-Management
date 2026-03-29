<?php

use App\Http\Controllers\apis\TransferController;
use Illuminate\Support\Facades\Route;


 Route::get('/hold', [TransferController::class, 'indexHoldTransfers'])->middleware(['auth.user']);

 Route::get('/prepared', [TransferController::class, 'indexPreparedTransfers'])->middleware(['auth.user']);

 Route::get('/dropped', [TransferController::class, 'indexDroppedTransfers'])->middleware(['auth.user']);

 Route::get('/verified', [TransferController::class, 'indexVerifiedTransfers'])->middleware(['auth.user']);

 Route::post('/sync', [TransferController::class, 'sync']);

 Route::get('/summary', [TransferController::class, 'getSummary'])->middleware(['auth.user']);


 Route::get('/{id}', [TransferController::class, 'findTransfer'])->middleware(['auth.user']);

 Route::post('/prepare/{id}', [TransferController::class, 'prepare'])->middleware(['auth.user']);

 Route::post('/drop/{id}', [TransferController::class, 'drop'])->middleware(['auth.user','validate.dropReason']);

 Route::get('/dropped-summary', [TransferController::class, 'droppedTransfersSummary'])->middleware(['auth.user']);


Route::post('/verify/manual/{id}', [TransferController::class, 'verifyManual'])->middleware(['auth.user']);

Route::post('/reject/{id}', [TransferController::class, 'reject'])->middleware(['auth.user']);