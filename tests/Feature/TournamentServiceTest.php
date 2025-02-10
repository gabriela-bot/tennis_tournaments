<?php

namespace Tests\Feature;

use App\Enum\Category;
use App\Enum\Status;
use App\Models\Player;
use App\Models\SingleMatches;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TournamentServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     *
     * A basic feature test example.
     */
    public function test_new_tournament_single(): void
    {
        $date = now()->format('Y-m-d');

        $attributes = [
            'name' => 'Open Championship',
            'players' => 32,
            "category" => 'men',
            'team' => 0,
            'date' => $date,
            'status' => Status::PENDING
        ];

        $tournament = (new \App\Services\TournamentService())->newTournament($attributes);

        $this->assertDatabaseHas('tournaments', $attributes);

        $this->assertInstanceOf(Tournament::class, $tournament);

        $this->assertEquals('Open Championship', $tournament->name);
        $this->assertEquals(Category::MEN, $tournament->category);
        $this->assertEquals(32, $tournament->players);
        $this->assertEquals(0, $tournament->team);
        $this->assertEquals($date, $tournament->date);
    }

    public function test_create_tournament_single(): void
    {
        $date = now()->format('Y-m-d');

        $attributes = [
            'name' => 'Us OPEN',
            'players' => 16,
            "category" => Category::WOMEN,
            'team' => 0,
            'date' => $date,
            'status' => Status::PENDING
        ];

        $tournament = (new \App\Services\TournamentService())->createTournament($attributes);

        $this->assertDatabaseHas('tournaments', $attributes);

        $this->assertDatabaseCount('single_matches', 8);

        $this->assertInstanceOf(Tournament::class, $tournament);

    }

    public function test_create_tournament_and_play_double(): void
    {
        $date = now()->format('Y-m-d');

        $attributes = [
            "category" => Category::WOMEN,
            "players" => 16,
            'team' => 1
        ];

        $tournament = (new \App\Services\TournamentService())->createTournamentAndPlay($attributes);

        $this->assertDatabaseCount('double_matches', 7);
        $this->assertDatabaseCount('groups', 8);

        // Verificar que la instancia devuelta es un objeto Tournament
        $this->assertInstanceOf(Tournament::class, $tournament);

        $this->assertEquals(Status::FINISH, $tournament->status);

    }

    public function test_create_player_tournament_single(): void
    {

        $attributes = [
            "category" => Category::WOMEN,
            "players" => 16,
            'team' => 0
        ];

        $service = (new \App\Services\TournamentService());
        $service->tournament = Tournament::factory()->create($attributes);

        $tournament = $service->createPlayerTournament();

        $this->assertDatabaseCount('single_matches', 8);

        $this->assertEmpty($tournament);


    }

    public function test_create_matches_for_player(): void
    {

        $attributes = [
            "category" => Category::WOMEN,
            "players" => 16,
            'team' => 0
        ];

        $players = Player::factory()->withCategory(Category::WOMEN)->count(16)->create();

        $service = (new \App\Services\TournamentService());
        $service->tournament = Tournament::factory()->create($attributes);

        $tournament = $service->createMatchesForPlayer($players->pluck('id')->toArray());

        $this->assertDatabaseCount('single_matches', 8);

        $this->assertEmpty($tournament);

    }

    public function test_play_matches_singles(): void
    {
        $attributes = [
            "category" => Category::MEN,
            "players" => 16,
            'team' => 0
        ];

        $players = Player::factory()->withCategory(Category::MEN)->count(16)->create();
        $service = (new \App\Services\TournamentService());
        $service->tournament = Tournament::factory()->create($attributes);

        for($i = 0; $i < count($players); $i+=2){
            SingleMatches::factory()
                ->state([
                    'player_one' => $players[$i],
                    'player_two' => $players[$i+1],
                    'tournament_id' => $service->tournament->id,
                ])
                ->create();
        }

        $tournament = $service->playMatches($players->pluck('id')->toArray());

        $this->assertDatabaseCount('single_matches', 15);

        $this->assertEmpty($tournament);
    }

    public function test_all_tournaments(): void
    {
        Tournament::factory()->count(2)
            ->state(new Sequence(
                ['name' => 'Prueba Open'],
                ['name' => 'Test Open'],
            ))->create();

        $request = request();
        $request->merge([
            'name' => 'Prueba Open'
        ]);

        $service = (new \App\Services\TournamentService());
        $tournamentList = $service->allTournaments();
        $this->assertIsObject($tournamentList);
    }


}
