<?php

use App\Http\Controllers\apis\TransferIssueController;
use Illuminate\Support\Facades\Route;


  Route::post('/report/{id}', [TransferIssueController::class, 'reportIssue'])
  ->middleware(['validate.transfer.issue','auth.user']);