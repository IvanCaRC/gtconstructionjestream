<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidaCuentaBancaria implements Rule
{
    public function passes($attribute, $value)
    {
        // Validar que solo contenga números y tenga una longitud válida (10, 12, 16 o 20 dígitos)
        return preg_match('/^\d{10}$|^\d{12}$|^\d{16}$|^\d{20}$/', $value);
    }

    public function message()
    {
        return 'El número de cuenta solo debe contener numeros en longitudes especificas (10, 12, 16 o 20)';
    }
}
