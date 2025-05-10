<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidaClaveBancaria implements Rule
{
    public function passes($attribute, $value)
    {
        // Validar que tenga exactamente 18 dígitos y solo números
        return preg_match('/^\d{18}$/', $value) === 1;
    }

    public function message()
    {
        return 'La CLABE debe contener exactamente 18 dígitos numéricos.';
    }
}
