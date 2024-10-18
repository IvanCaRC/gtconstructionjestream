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
    public function verUsuario($idcurso)
    {
        // Lógica para mostrar los usuarios
        
        return view('admin.userView');
    }
}