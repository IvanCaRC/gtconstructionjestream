<?php
namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();
        
        // Verificar si el usuario tiene algún rol y redirigir según corresponda
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboardAdmin'));
        } elseif ($user->hasRole('Ventas')) {
            return redirect()->intended(route('ventas.dashboardVentas'));
        } elseif ($user->hasRole('Compras')) {
            return redirect()->intended(route('compras.dashboardCompras'));
        } elseif ($user->hasRole('Finanzas')) {
            return redirect()->intended(route('finanzas.dashboardFinanzas'));
        } else {
            // Redirección por defecto si no tiene ningún rol específico
            return redirect()->intended(route('admin.dashboardAdmin'));
        }
    }
}