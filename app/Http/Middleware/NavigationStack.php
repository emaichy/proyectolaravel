<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NavigationStack
{
    protected $maxStackSize = 15;
    public function handle(Request $request, Closure $next)
    {
        $excludedRoutes = [
            'api.*',
            'ajax.*',
            'login',
            'logout',
            'volver',
            'estados.*',
            'municipios.*',
        ];
        $excludedPaths = [
            'municipiosEstado/*',
            'estados/*',
            'pacientes/list',
            'grupos_maestros/ajax/*'
        ];
        if ($request->query('reset_nav')) {
            session()->forget('nav_stack');
        }
        if (!$request->isMethod('get') || $request->ajax()) {
            return $next($request);
        }
        foreach ($excludedRoutes as $excluded) {
            if ($request->routeIs($excluded)) {
                return $next($request);
            }
        }
        foreach ($excludedPaths as $excluded) {
            if ($request->is($excluded)) {
                return $next($request);
            }
        }
        $stack = session('nav_stack', []);
        $current = $request->fullUrl();
        while (end($stack) === $current) {
            array_pop($stack);
        }
        $stack[] = $current;
        if (count($stack) > $this->maxStackSize) {
            $stack = array_slice($stack, -$this->maxStackSize);
        }
        session(['nav_stack' => $stack]);
        return $next($request);
    }
}