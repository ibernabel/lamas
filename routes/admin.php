<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {


  Route::get('', function () {
    return view('admin.admin');
  })->name('admin.dashboard');

  Route::get('/users', function () {
    return 'Vista administracion de usuarios';
    //return view('admin.users');
  })->name('users');

  Route::get('/application', function () {
    return redirect('admin.dashboard');
  })->name('application');

  Route::get('/application/{id}', function () {
    return 'Vista administracion de solicitudes';
    //return view('application');
  })->name('application');

});