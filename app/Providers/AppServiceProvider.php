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
            $maestroSesion = null;
            $alumnoSesion = null;
            if (Auth::check()) {
                if (Auth::user()->maestro) {
                    $maestroSesion = Auth::user()->maestro;
                }
                if (Auth::user()->alumno) {
                    $alumnoSesion = Auth::user()->alumno;
                }
            }
            $view->with('maestroSesion', $maestroSesion)->with('alumnoSesion', $alumnoSesion);
        });
    }
}
