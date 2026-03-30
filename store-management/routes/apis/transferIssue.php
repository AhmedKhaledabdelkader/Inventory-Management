<?php

use App\Http\Controllers\apis\TransferIssueController;
use Illuminate\Support\Facades\Route;


  Route::post('/report/{id}', [TransferIssueController::class, 'reportIssue'])
  ->middleware(['auth.user','roles:Quality Control','validate.transfer.issue']);