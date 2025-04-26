<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Lógica para mostrar los usuarios
        return view('admin.users');
    }
    public function verUsuario($iduser)
    {
        return view('admin.userView', ['iduser' => $iduser]);
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
}