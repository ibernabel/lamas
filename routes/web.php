<?php

use App\Models\CreditRisk;
use App\Models\LoanApplication;
use App\Models\User;
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


  Route::get('/pruebas', function () {
    $user = User::where('id', 1)->first();
    $userName = $user->name;
    //return 'Pagina de Pruebas ' . $admin;
    return view('pruebas', compact('userName'));
  })->name('pruebas');

  /**
   * Attach random risks to loan applications:
   */
  //Route::get('/riesgos', function () {
  //  $loanApplications = LoanApplication::all();
  //  $risks = CreditRisk::all();

  //  $loanApplications->each(function ($loanApplication) use ($risks) {
  //    $riskIds = $risks->random(5)->pluck('id')->unique()->toArray();
  //    $loanApplication->risks()->sync($riskIds);
  //  });

  //  return $loanApplications;
  //});
});
