<?php

namespace App\Http\Middleware;

use App\Filament\Pages\ThreeDash;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectThreecommerceDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->is('admin')
            && auth()->check()
            && auth()->user()->hasRole('threecommerce')
        ) {
            return redirect()->to(ThreeDash::getUrl());
        }

        return $next($request);
    }
}
