<?php

namespace App\Helpers;

class RandomGroupGenerator
{
    public static function sliceGroup($array) {
        shuffle($array); // Mezcla aleatoriamente el array
        $group = [];

        // Divide el array en grupos de 2
        for ($i = 0; $i < count($array); $i += 2) {
            $group[] = array_slice($array, $i, 2); // Crea grupos de 2
        }

        return $group;
    }
}
