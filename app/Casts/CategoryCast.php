<?php

namespace App\Casts;

use App\Category;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CategoryCast implements CastsAttributes
{


    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Category) {
            return $value;
        }
        return Category::tryFrom($value) ?? Category::MEN;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Category) {
            return $value;
        }
        if (!Category::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid category");
        }
        return $value;
    }
}
