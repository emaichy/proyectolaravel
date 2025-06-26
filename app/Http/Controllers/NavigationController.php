<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function back()
    {
        $stack = session()->get('nav_stack', []);
        array_pop($stack);
        $backUrl = array_pop($stack); 
        session(['nav_stack' => $stack]);
        return redirect($backUrl ?? route('home'));
    }
}
