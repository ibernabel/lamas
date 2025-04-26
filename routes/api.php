<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LoanApplicationController as ApiLoanApplicationController;
use App\Http\Controllers\Api\V1\CustomerController; // Add this line

// Group related routes with common middleware
Route::prefix('v1')->middleware([
      'auth:sanctum'
])->group(function () {
    // API Resource routes for loan applications
    // Use apiResource for standard API methods
    //->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('loan-applications', ApiLoanApplicationController::class);
    Route::post('/loan-applications/simple', [ApiLoanApplicationController::class, 'simpleLoanApplication'])->name('api.v1.loan-applications.simple.store'); // Added route for simple store

    // API Resource routes for customers
    Route::apiResource('customers', CustomerController::class)->except(['create', 'edit']); // Exclude web-specific routes

    // Route to check if customer NID exists (keep this custom route)
    Route::post('/customers/nid/exists', [CustomerController::class, 'checkNidExists'])->name('api.v1.customers.nid.exists');
    // Route to create a customer with minimal data
    Route::post('/customers/simple', [CustomerController::class, 'simpleStore'])->name('api.v1.customers.simple.store');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tokenStatus', [AuthController::class, 'tokenStatus']); // Updated to use tokenStatus method
});

// Group related routes without Auth middleware
Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
});
