<?php

use App\Http\Controllers\apis\TransferController;
use Illuminate\Support\Facades\Route;


 Route::get('/hold', [TransferController::class, 'indexHoldTransfers'])->middleware(['auth.user',"role:Picker,Quality Control"]);

 Route::get('/prepared', [TransferController::class, 'indexPreparedTransfers'])->middleware(['auth.user',
"role:Picker,Quality Control"]);

 Route::get('/dropped', [TransferController::class, 'indexDroppedTransfers'])->middleware(['auth.user',
"role:Picker,Quality Control"]);

 Route::get('/verified', [TransferController::class, 'indexVerifiedTransfers'])->middleware(['auth.user','role:Quality Control']);

 Route::post('/sync', [TransferController::class, 'sync']);

 Route::get('/summary', [TransferController::class, 'getSummary'])->middleware(['auth.user','role:Picker']);


 Route::get('/{id}', [TransferController::class, 'findTransfer'])->middleware(['auth.user']);

 Route::post('/prepare/{id}', [TransferController::class, 'prepare'])->middleware(['auth.user',"role:Picker"]);

 Route::post('/drop/{id}', [TransferController::class, 'drop'])->middleware(['auth.user','role:Picker','validate.dropReason']);

 Route::get('/stock-control/dropped-summary', [TransferController::class, 'droppedTransfersSummary'])->middleware(['auth.user',
'role:Stock Control']);


Route::post('/verify/manual/{id}', [TransferController::class, 'verifyManual'])->middleware(['auth.user','role:Quality Control']);

Route::post('/verify/scan-barcode/{id}', [TransferController::class, 'scan'])->middleware(['auth.user'
,'validate.barcode','role:Quality Control']);

Route::post('/reject/{id}', [TransferController::class, 'reject'])->middleware(['auth.user']);



Route::get('/erp/status', [TransferController::class, 'erpStatus'])->middleware(['auth.user']);


Route::get('/qc/dashboard', [TransferController::class, 'qcDashboard'])->middleware(['auth.user','role:Quality Control']);