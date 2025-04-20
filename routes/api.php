<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\LoanApplicationController as ApiLoanApplicationController;

// Group related routes with common middleware
Route::prefix('v1')->middleware([
//  'auth:sanctum'
])->group(function () {
  // API Resource routes for loan applications
  // Use apiResource for standard API methods
  //->only(['index', 'show', 'store', 'update', 'destroy']);
  Route::apiResource('loan-applications', ApiLoanApplicationController::class);
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);

// Routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tokenStatus', [AuthController::class, 'tokenStatus']); // Updated to use tokenStatus method
});
