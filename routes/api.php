<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\LoanApplicationController as ApiLoanApplicationController;

// Group related routes with common middleware
//Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
Route::prefix('v1')->middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  // API Resource routes for loan applications
  Route::apiResource('loan-applications', ApiLoanApplicationController::class); // Use apiResource for standard API methods
});

// Login route (moved back from web.php)
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function (Request $request) {
  $request->user()->currentAccessToken()->delete();
  return response()->json([
    'message' => 'Logged out successfully',
    'Status Code' => 200,
  ], 200);
});
Route::get('/login', function () {
  return response()->json([
    'message' => 'Not Found',
    'Status Code' => 404,

  ], 404);
});
Route::get('/logout', function () {
  return response()->json([
    'message' => 'Not Found',
    'Status Code' => 404,

  ], 404);
});
Route::get('/register', function () {
  return response()->json([
    'message' => 'Not Found',
    'Status Code' => 404,

  ], 404);
});
Route::get('/password/reset', function () {
  return response()->json([
    'message' => 'Not Found',
    'Status Code' => 404,

  ], 404);
});
