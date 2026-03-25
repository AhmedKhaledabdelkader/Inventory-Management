<?php


use App\Http\Controllers\apis\NotificationController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth.user')->group(function () {

    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread', [NotificationController::class, 'unread']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/read/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
});




