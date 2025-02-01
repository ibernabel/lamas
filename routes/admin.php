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

  //Route::resource('loan-applications', LoanApplicationController::class)->names('loan-applications');

  Route::get('/loan-applications', [LoanApplicationController::class, 'index'])->name('loan-applications.index');
  Route::get('/loan-applications/datatable', [LoanApplicationController::class, 'datatable'])->name('loan-applications.datatable');
  Route::get('/loan-applications/{id}', [LoanApplicationController::class, 'show'])->name('loan-applications.show');
  Route::get('/loan-applications/{id}/edit', [LoanApplicationController::class, 'show'])->name('loan-applications.edit');

  //Redirect to admin dashboard
  //Route::get('/loan-application', function () {
  //  return redirect('admin');
  //})->name('loan-application');

  //Route::get('/application/{id}', function () {
  //  return 'Vista administracion de solicitudes';
  //  //return view('application');
  //})->name('application');

});