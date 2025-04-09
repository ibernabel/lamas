<?php

use App\Http\Controllers\LoanApplicationController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  Route::get('', function () {
    return view('admin.admin');
  })->name('dashboard');

  Route::get('/users', function () {
    return 'Vista administracion de usuarios';
    //return view('admin.users');
  })->name('users');

  // Loan Applications Routes
  Route::get('/loan-applications', [LoanApplicationController::class, 'index'])->name('loan-applications.index');
  Route::get('/loan-applications/datatable', [LoanApplicationController::class, 'datatable'])->name('loan-applications.datatable');
  Route::get('/loan-applications/create', [LoanApplicationController::class, 'create'])->name('loan-applications.create');
  Route::post('/loan-applications', [LoanApplicationController::class, 'store'])->name('loan-applications.store');
  Route::get('/loan-applications/{id}', [LoanApplicationController::class, 'show'])->name('loan-applications.show');
  Route::get('/loan-applications/{id}/edit', [LoanApplicationController::class, 'edit'])->name('loan-applications.edit');
  Route::put('/loan-applications/{id}', [LoanApplicationController::class, 'update'])->name('loan-applications.update');
  Route::delete('/loan-applications/{id}', [LoanApplicationController::class, 'destroy'])->name('loan-applications.destroy');
});
