<?php

namespace Database\Factories;

use App\Category;
use App\Models\Player;
use App\Services\Attributes;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Monolog\Level;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' =>  $this->faker->randomElement(Category::values()),
            'name' => $this->faker->name(),
            'level' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function withCategory(Category $category = null)
    {
        return $this->state(function (array $attributes) use ($category) {
            $attributes = new Attributes($attributes['level'], $category);
            return [
                'category' => $category,
                'power'=> $attributes->getPower(),
                'speed'=> $attributes->getSpeed(),
                'reaction'=> $attributes->getReaction(),
            ];
        });
    }

}
