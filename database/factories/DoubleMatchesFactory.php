<?php

namespace Database\Factories;

use App\Category;
use App\Models\Groups;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoubleMatches>
 */
class DoubleMatchesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tournament = $this->state['tournament_id'] ?? Tournament::factory();
        $category = $this->faker->randomElement(Category::values());
        $group_one = Groups::factory()->withCategory(Category::tryFrom($category));
        $group_two = Groups::factory()->withCategory(Category::tryFrom($category));
        return [
            'group_one' => $group_one,
            'group_two' => $group_two,
            'tournament_id' => $tournament,
        ];
    }

    public function withCategory(Category $category = null): DoubleMatchesFactory|Factory
    {
        return $this->state(function (array $attributes) use ($category) {
            $category = $category;
            $group_one = Groups::factory()->withCategory($category);
            $group_two = Groups::factory()->withCategory($category);
            return [
                'group_one' => $group_one,
                'group_two' => $group_two,
            ];
        });
    }
}
