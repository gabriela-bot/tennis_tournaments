<?php

namespace Database\Factories;

use App\Enum\Category;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Groups>
 */
class GroupsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = $this->faker->randomElement(Category::values());
        return [
            'name' => $this->faker->country(),
            'player_one' => Player::factory()->withCategory(Category::tryFrom($category)),
            'player_two' => Player::factory()->withCategory(Category::tryFrom($category)),
        ];
    }

    public function withCategory(Category $category = null)
    {
        return $this->state(function (array $attributes) use ($category) {
            return [
                'player_one' => Player::factory()->withCategory($category),
                'player_two' => Player::factory()->withCategory($category),
            ];
        });
    }

}
