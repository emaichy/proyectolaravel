<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            $maestro = null;
            $alumno = null;

            if (Auth::check()) {
                if (Auth::user()->maestro) {
                    $maestro = Auth::user()->maestro;
                }
                if (Auth::user()->alumno) {
                    $alumno = Auth::user()->alumno;
                }
            }

            $view->with('maestro', $maestro)->with('alumno', $alumno);
        });
    }
}
