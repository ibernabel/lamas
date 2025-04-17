<?php

use App\Models\CreditRisk;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
  return view('welcome');
});
Route::get('/terms', function () {
  return view('terms');
})->name('terms.show');
Route::get('/policy', function () {
  return view('policy');
  })->name('policy.show');

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  Route::get('/dashboard', function () {
    return view('dashboard');
  })->name('dashboard');


});
