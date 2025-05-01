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
  'approved', // Agregar middleware de aprobación
])->group(function () {

  Route::get('/dashboard', function () {
    return view('admin.admin');
    //return view('admin.loan-applications.index');
  })->name('dashboard');

  Route::get('/dashboard-raw', function () {
    //return view('admin.admin');
    return view('dashboard');
  })->name('dashboard-raw');
});

// Rutas para invitaciones y aprobación de usuarios
Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
  'approved'
])->prefix('admin')->group(function () {
  // Rutas de invitaciones
  Route::resource('invitations', \App\Http\Controllers\UserInvitationController::class)
      ->except(['edit', 'update', 'show'])
      ->middleware('can:user.create');

  // Rutas de aprobación de usuarios
  Route::get('/users/pending', [\App\Http\Controllers\UserApprovalController::class, 'index'])
      ->name('users.pending')
      ->middleware('can:user.edit');

  Route::post('/users/{user}/approve', [\App\Http\Controllers\UserApprovalController::class, 'approve'])
      ->name('users.approve')
      ->middleware('can:user.edit');

  Route::delete('/users/{user}/reject', [\App\Http\Controllers\UserApprovalController::class, 'reject'])
      ->name('users.reject')
      ->middleware('can:user.edit');
});
