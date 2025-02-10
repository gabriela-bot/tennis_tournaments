<?php

namespace App\Rules;

use App\Models\Player;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class checkCategoryPlayers implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(is_array($value)){
            $players = Player::whereIn('id', $value)->select('category')->groupBy('category')->get();
            if($players->count() > 1){
                $fail('La categoria de todos los jugadores debe coicidir');
            }
            if(request()->category){
                if(request()->category != $players->value('category')->value){
                    $fail('La categoria debe coincidir con el torneo');
                }
            }
        }
    }
}
