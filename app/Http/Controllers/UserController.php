<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Lógica para mostrar los usuarios
        return view('admin.users');
    }

    public function verRoles()
    {
        return view('admin.roles');
    }
    public function verCancelaciones()
    {
        return view('admin.cancelaciones');
    }

    public function verPerfil()
    {
        return view('profile.profileView');
    }

    public function verUsuario($iduser)
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
        $rol = $usuario->getRoleNames()->first(); // Obtener el primer rol del usuario

        $datos = [];

        // Si el rol es "Compras", calcular métricas de ventas
        if ($rol === 'Compras') {
            $ventasMensuales = DB::table('orden_venta')
                ->where('id_usuario', $usuario->id)
                ->where('estado', 0) // Estado 0 = completadas
                ->whereMonth('created_at', now()->month) // Mes actual
                ->sum('monto');

            $ventasAnuales = DB::table('orden_venta')
                ->where('id_usuario', $usuario->id)
                ->where('estado', 0)
                ->whereYear('created_at', now()->year) // Año actual
                ->sum('monto');

            $proyectos = DB::table('orden_venta')
                ->where('id_usuario', $usuario->id)
                ->where('estado', 1) // Estado 1 = pendientes
                ->count();

            $pendientes = DB::table('orden_venta')
                ->where('id_usuario', $usuario->id)
                ->where('estado', 1)
                ->count();

            $datos = [
                'ventas_mensuales' => $ventasMensuales ?? 0,
                'ventas_anuales' => $ventasAnuales ?? 0,
                'proyectos' => $proyectos ?? 0,
                'pendientes' => $pendientes ?? 0,
            ];
        }

        return view('admin.userView', [
            'iduser' => $iduser,
            'rol' => $rol,
            'datos' => $datos,
        ]);
    }
}