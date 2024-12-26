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

  //Route::resource('loan-application', LoanApplicationController::class)->names('loan-application');

  Route::get('/loan-application', [LoanApplicationController::class, 'index'])->name('loan-application.index');
  Route::get('/loan-application/{id}', [LoanApplicationController::class, 'show'])->name('loan-application.show');

  //Redirect to admin dashboard
  //Route::get('/loan-application', function () {
  //  return redirect('admin');
  //})->name('loan-application');

  //Route::get('/application/{id}', function () {
  //  return 'Vista administracion de solicitudes';
  //  //return view('application');
  //})->name('application');

});