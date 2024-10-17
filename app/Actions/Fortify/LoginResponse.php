<?php
namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended(route('admin.dashboardAdmin')); // Cambia '/nueva-ruta' por la ruta deseada
    }
}
