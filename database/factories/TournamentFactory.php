<?php

namespace Database\Factories;

use App\Enum\Category;
use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(Category::values()),
            'name' => $this->faker->name() . ' Open' ,
            'date' => $this->faker->dateTimeBetween('today', '+1 month'),
            'players' => function (array $attributes) {
                $q = $this->faker->numberBetween(1, 5);
                return pow(2, $q);
            },
            'status' => $this->faker->randomElement(Status::values()),
            'team' => $this->faker->boolean()
        ];
    }
}
