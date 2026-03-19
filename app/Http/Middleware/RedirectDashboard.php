<?php

namespace App\Http\Middleware;

use App\Filament\Pages\ThreeDash;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Filament\Pages\Clienti\Pacchetti;
class RedirectDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->is('admin')
            && auth()->check()
            && auth()->user()->hasRole('threecommerce')
        ) {
            return redirect()->to(ThreeDash::getUrl());
        }elseif (
            $request->is('admin')
            && auth()->check()
            && auth()->user()->hasRole('cliente')
        ) {
            return redirect()->to(Pacchetti::getUrl());
        }

        return $next($request);
    }
}
