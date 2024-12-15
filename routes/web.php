<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
  return view('welcome');
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  Route::get('/dashboard', function () {
    return view('dashboard');
  })->name('dashboard');

  Route::get('/users', function () {
    return 'Vista administracion de usuarios';
    //return view('users');
  })->name('users');

  Route::get('/application', function () {
    return redirect('dashboard');
  })->name('application');

  Route::get('/application/{id}', function () {
    return 'Vista administracion de solicitudes';
    //return view('application');
  })->name('application');

});

Route::get('/prueba', function () {
  $admin = Role::find('admin');
  return 'Pagina de Pruebas ' . $admin;
  //return view('home');
})->name('prueba');
