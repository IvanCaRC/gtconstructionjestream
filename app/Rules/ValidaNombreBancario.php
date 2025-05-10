<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidaNombreBancario implements Rule
{
    public function passes($attribute, $value)
    {
        // Validar que solo contenga letras y espacios, evitando múltiples espacios
        return preg_match('/^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/', $value) && !preg_match('/\s{2,}/', $value);
    }

    public function message()
    {
        return 'El nombre del titular solo puede contener letras y espacios simples, sin caracteres especiales ni múltiples espacios consecutivos.';
    }
}
