<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAutenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        // A sua lógica de checagem da sessão que estava no arquivo de rotas:
        if (!session()->has('admin_logado')) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
