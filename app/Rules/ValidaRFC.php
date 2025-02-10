<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidaRfc implements Rule
{
    public function passes($attribute, $value)
    {
        // Validar longitud del RFC: 13 para personas físicas y 12 para personas morales
        $length = strlen($value);
        if ($length !== 13 && $length !== 12) {
            return false;
        }

        // Validar formato del RFC usando expresiones regulares
        $regexFisica = '/^[A-ZÑ&]{4}\d{6}[A-Z\d]{3}$/'; // Personas físicas
        $regexMoral = '/^[A-Z]{3}\d{6}[A-Z\d]{3}$/'; // Personas morales

        return preg_match($regexFisica, $value) || preg_match($regexMoral, $value);
    }

    public function message()
    {
        return 'El RFC no es válido.';
    }
}

