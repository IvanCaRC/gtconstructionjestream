<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener la vista según el rol del usuario
        $view = $this->getRoleView($user);

        // Redireccionar a la vista correspondiente
        return view($view);
    }

    private function getRoleView($user)
    {
        $role = $user->roles()->first()->name;

        switch ($role) {
            case 'Administrador':
                return 'admin.dashboardAdmin';
            case 'Ventas':
                return 'ventas.dashboardVentas';
            case 'Compras':
                return 'compras.dashboardCompras';
            case 'Finanzas':
                return 'finanzas.dashboardFinanzas';
            default:
                return 'home';
        }
    }
}










