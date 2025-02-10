<?php

namespace Database\Factories;

use App\Enum\Category;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SingleMatches>
 */
class SingleMatchesFactory extends Factory
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
            'player_one' => Player::factory()->withCategory(Category::tryFrom($category)),
            'player_two' => Player::factory()->withCategory(Category::tryFrom($category)),
            'tournament_id' => Tournament::factory()->state(['category' => $category]),
            'match_number' => 1
        ];
    }


    public function withCategory(Category $category = null): SingleMatchesFactory|Factory
    {
        return $this->state(function (array $attributes) use ($category) {
            return [
                'player_one' => Player::factory()->withCategory($category),
                'player_two' => Player::factory()->withCategory($category),
            ];
        });
    }

    public function withTournament($tournament): SingleMatchesFactory|Factory
    {
        return $this->state(function (array $attributes) use ($tournament) {
            if($tournament){
                return [
                    'tournament_id' => $tournament,
                ];
            } else {
                return [
                    'tournament_id' => Tournament::factory(),
                ];
            }
        });
    }

}
