<?php

use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\CostumerController; // Add this line
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  Route::get('', function () {
    return view('admin.admin');
    //return view('admin.loan-applications.index'); // Cambia temporalmente a la vista de index de loan-applications, hasta que se creemos la vista de admin correctamente

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

  // Customer Routes (Using 'customers' as the resource name based on controller comments)
  Route::get('/customers', [CostumerController::class, 'index'])->name('customers.index');
  Route::get('/customers/datatable', [CostumerController::class, 'datatable'])->name('customers.datatable');
  Route::get('/customers/create', [CostumerController::class, 'create'])->name('customers.create');
  Route::post('/customers', [CostumerController::class, 'store'])->name('customers.store');
  Route::get('/customers/{id}', [CostumerController::class, 'show'])->name('customers.show');
  Route::get('/customers/{id}/edit', [CostumerController::class, 'edit'])->name('customers.edit');
  Route::put('/customers/{id}', [CostumerController::class, 'update'])->name('customers.update');
  Route::delete('/customers/{id}', [CostumerController::class, 'destroy'])->name('customers.destroy');
});
