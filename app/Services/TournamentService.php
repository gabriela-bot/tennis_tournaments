<?php

namespace App\Services;

use App\Enum\Status;
use App\Http\Resources\Groups\GroupCollection;
use App\Http\Resources\Matches\DoubleMatchesCollection;
use App\Http\Resources\Matches\DoubleMatchesResponse;
use App\Http\Resources\Matches\SingleMatchesCollection;
use App\Http\Resources\Matches\SingleMatchesResponse;
use App\Http\Resources\Players\PlayerCollection;
use App\Http\Resources\Tournament\TournamentResource;
use App\Models\DoubleMatches;
use App\Models\Groups;
use App\Models\Player;
use App\Models\SingleMatches;
use App\Models\Tournament;
use App\Repositories\Matches;
use Illuminate\Support\Facades\Request;

class TournamentService
{
    public Tournament $tournament;
    public $doubleMatches;
    public $singleMatches;

    public function newTournament(array $attributes) : Tournament
    {
        $this->tournament = Tournament::factory()->state($attributes)->create();
        return $this->tournament;
    }

    public function createTournament(array $attributes)  : Tournament
    {
        $this->tournament = $this->newTournament($attributes);
        $this->createPlayerTournament();
        return $this->tournament;
    }

    public function createTournamentAndPlay(array $attributes) : Tournament
    {
        $this->tournament = $this->newTournament($attributes);
        $this->createPlayerTournament();
        $this->playMatches();
        return $this->tournament;
    }

    public function createPlayerTournament() : void
    {
        if($this->tournament->team){
            $groupsQ = $this->tournament->players/2;
            DoubleMatches::factory()
                ->count($groupsQ/2)
                ->withCategory($this->tournament->category)
                ->state([
                    'tournament_id' => $this->tournament->id,
                ])->create();
        } else{
            $playersQ = $this->tournament->players;
            SingleMatches::factory()
                ->count($playersQ/2)
                ->withCategory($this->tournament->category)
                ->state([
                    'tournament_id' => $this->tournament->id,
                ])
                ->create();
        }
    }

    public function createMatchesForPlayer(array $players) : void
    {
        for($i = 0; $i < count($players); $i+=2){
            SingleMatches::factory()
                ->state([
                    'player_one' => $players[$i],
                    'player_two' => $players[$i+1],
                    'tournament_id' => $this->tournament->id,
                ])
                ->create();
        }
    }

    public function playMatches() : void
    {
        $matches = new Matches($this->tournament);
        if($this->tournament->team){
            $matches->double();
        } else{
            $matches->simple();
        }
        $this->tournament->update([
            'status' => Status::FINISH,
        ]);
    }

    public function allTournaments(Request $request = null)
    {
        if(!$request) $request = request();
        $parameter = $request->only([
            'name',
            'category',
            'status',
            'team',
            'players',
            'date'
        ]);

        $paginate = $request->get('paginate', false);
        $order = $request->get('order', 'id');
        $sort = $request->get('sort', 'asc');

        $query = Tournament::where(
            function ($query) use ($parameter) {
                foreach ($parameter as $key => $value){
                    if($key == 'name'){
                        $query->where($key, 'LIKE','%'.$value. '%');
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        )->orderBy($order, $sort);
        if($paginate){
            return $query->paginate();
        }
        return  $query->get();
    }

    public function returnTournament() : array
    {
        if($this->tournament->team){
            $lastMatch = $this->tournament->doubleMatches()->whereNotNull('group_winner_id')->orderBY('id', 'DESC')->first();
            $groups = Groups::whereHas('doubleMatchesOne', fn($query) => $query->where('tournament_id', $this->tournament->id))
                ->orWhereHas('doubleMatchesTwo', fn($query) => $query->where('tournament_id', $this->tournament->id))
                ->get();
            return [
                'tournament' => new TournamentResource($this->tournament),
                'lastMatch' => new DoubleMatchesResponse($lastMatch),
                'matches' => new DoubleMatchesCollection($this->tournament->doubleMatches()->orderBy('id', 'ASC')->get()),
                'groups' => new GroupCollection($groups)
            ];
        } else {
            $lastMatch = $this->tournament->singleMatches()->whereNotNull('winner_id')->orderBY('id', 'DESC')->with([
                'winner',
                'secondPlayer'
            ])->first();
            $players = Player::whereHas('matchesPlayerTwo', fn($query) => $query->where('tournament_id', $this->tournament->id))
                ->orWhereHas('matchesPlayerOne', fn($query) => $query->where('tournament_id', $this->tournament->id))
                ->get();
            return [
                'tournament' => new TournamentResource($this->tournament),
                'lastMatch' => new SingleMatchesResponse($lastMatch),
                'matches' => new SingleMatchesCollection($this->tournament->singleMatches()->orderBy('id', 'ASC')->get()),
                'players' => new PlayerCollection($players)
            ];
        }
    }
}
