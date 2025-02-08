<?php

namespace App\Services;

use App\Category;
use App\Models\DoubleMatches;
use App\Models\Groups;
use App\Models\Player;
use App\Models\SingleMatches;
use App\Models\Tournament;
use App\Status;

class TournamentService
{
    public Tournament $tournament;


    public function createTournamentAndPlay(array $attributes) : Tournament
    {
        $this->tournament = Tournament::factory()->state($attributes)->create();
        $this->createPlayerTournament();
        $this->createMatches();

        return $this->tournament;
    }

    public function createTournament(array $attributes) : Tournament
    {
        $this->tournament = Tournament::factory()->state($attributes)->create();
        $this->createPlayerTournament();
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

    public function createMatches() : void
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
}
