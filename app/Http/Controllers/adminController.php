<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class adminController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener la vista según el rol del usuario
        $view = $user->getRoleView();

        // Redireccionar a la vista correspondiente
        return view($view);
    }
}









