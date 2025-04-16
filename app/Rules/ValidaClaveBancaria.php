<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidaClaveBancaria implements Rule
{
    public function passes($attribute, $value)
    {
        // Verificar que la CLABE tenga exactamente 18 dígitos y solo contenga números
        if (!preg_match('/^\d{18}$/', $value)) {
            return false;
        }

        // Pesos de los dígitos según el algoritmo oficial
        $pesos = [3, 7, 1];

        // Calcular la suma ponderada
        $suma = 0;
        for ($i = 0; $i < 17; $i++) {
            $suma += intval($value[$i]) * $pesos[$i % 3];
        }

        // Obtener dígito de control esperado
        $digito_control = (10 - ($suma % 10)) % 10;

        // Comparar con el último dígito de la CLABE
        return intval($value[17]) === $digito_control;
    }

    public function message()
    {
        return 'La CLABE ingresada no es válida. Verifica los datos.';
    }
}
