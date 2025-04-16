<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
      //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
      $this->app['view']->addNamespace('adminlte', resource_path('views/partials/navbar'));
      Blade::component('loan-status', \App\View\Components\LoanStatus::class);
  }
}
