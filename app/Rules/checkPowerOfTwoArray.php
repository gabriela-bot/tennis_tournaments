<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class checkPowerOfTwoArray implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!is_array($value)){
            $fail("Debe enviar un array de jugadores");
        }

        $count = count($value);

        if(!(($count > 0) && (($count & ($count - 1)) === 0))){
            $fail("El número no de $attribute no es valido");
        };
    }
}
