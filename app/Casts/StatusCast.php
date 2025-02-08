<?php

namespace App\Casts;

use App\Status;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class StatusCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Status) {
            return $value;
        }
        return Status::tryFrom($value) ??  Status::ACTIVE;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Status) {
            return $value;
        }
        if (!Status::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid status");
        }
        return $value;
    }
}
