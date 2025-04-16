<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoanApplicationController as ApiLoanApplicationController;

// Group related routes with common middleware
//Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
Route::prefix('v1')->group(function () {

    // API Resource routes for loan applications
    Route::apiResource('loan-applications', ApiLoanApplicationController::class); // Use apiResource for standard API methods
});
