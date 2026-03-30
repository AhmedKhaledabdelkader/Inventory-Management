<?php

use App\Http\Controllers\apis\DashboardController;
use App\Http\Controllers\apis\StoreManagerController;
use Illuminate\Support\Facades\Route;



 Route::get('/dashboard', [StoreManagerController::class, 'dashboard'])->middleware(['auth.user','role:Store Manager']);


Route::get('/onhold-transfers', [StoreManagerController::class, 'onHoldTransfers'])->middleware(['auth.user','role:Store Manager']);

Route::get('/verified-transfers', [StoreManagerController::class, 'verifiedTransfers'])->middleware(['auth.user','role:Store Manager']);

Route::get('/boxes', [StoreManagerController::class, 'boxes'])->middleware(['auth.user','role:Store Manager']);

Route::get('/available-boxes-for-lot', [StoreManagerController::class, 'availableBoxesForLot'])->middleware(['auth.user','role:Store Manager']);

Route::get('/lots', [StoreManagerController::class, 'lots'])->middleware(['auth.user','role:Store Manager']);

Route::get('/lots/in-transit', [StoreManagerController::class, 'inTransitLots'])->middleware(['auth.user','role:Store Manager']);

Route::get('/lots/delivered', [StoreManagerController::class, 'deliveredLots'])->middleware(['auth.user','role:Store Manager']);

Route::get('/lots/in-transit', [StoreManagerController::class, 'inTransitLots'])->middleware(['auth.user','role:Store Manager']);

Route::get('/lots/delivered', [StoreManagerController::class, 'deliveredLots'])->middleware(['auth.user','role:Store Manager']);

Route::post('/lots/mark-received', [StoreManagerController::class, 'markReceivedByCode'])->middleware(['auth.user','role:Store Manager',
'validate.lotCodeAndReciptNote']);

Route::post('/lots/confirm-receipt/{id}', [StoreManagerController::class, 'confirmReceipt'])->middleware(['auth.user',
'role:Store Manager','validate.reciptNote']);