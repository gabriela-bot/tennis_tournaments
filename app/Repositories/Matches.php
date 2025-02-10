<?php

namespace App\Repositories;

use App\Models\DoubleMatches;
use App\Models\SingleMatches;
use App\Models\Tournament;
use Illuminate\Support\Facades\Log;

class Matches
{

    public Tournament $tournament;

    /**
     * @param Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function double(): void
    {
        $doubleNotWinner = $this->tournament->doubleMatches()->whereNull('group_winner_id')->get();
        $totalMatches = ($this->tournament->players/2)-1;
        $totalPlayed = 0;
        $matchesNumber = 0;
        while ($totalPlayed != $totalMatches) {
            $playerRound = null;
            foreach ($doubleNotWinner as $double) {
                $matchesNumber++;
                $game = new Winners($double);
                $game->start();
                $double->update([
                    'group_winner_id' => $game->getWinner()->id,
                    'match_number' => $matchesNumber
                ]);
                if(!is_null($playerRound)) {
                    DoubleMatches::create([
                        'group_one' => $playerRound->id,
                        'group_two' => $game->getWinner()->id,
                        'tournament_id' => $this->tournament->id,
                        'match_number' => $matchesNumber
                    ]);
                    $playerRound = null;
                    $doubleNotWinner = $this->tournament->doubleMatches()->whereNull('group_winner_id')->get();
                    $totalPlayed++;
                    continue;
                }
                $playerRound = $game->getWinner();
                $totalPlayed++; // 4 // 2 // 1
            }
        }
    }

    public function simple(): void
    {
        $singleNotWinner = $this->tournament->singleMatches()->whereNull('winner_id')->get();
        $totalMatches = ($this->tournament->players/2);
        $totalPlayed = 0;
        $matchesNumber = 0;
        while ($totalPlayed != $totalMatches) {
            $playerRound = null;
            foreach ($singleNotWinner as $single) {
                $matchesNumber++;
                $game = new Winners($single);
                $game->start();
                $single->update([
                    'winner_id' => $game->getWinner()->id,
                    'match_number' => $matchesNumber
                ]);
                if(!is_null($playerRound)) {
                    SingleMatches::create([
                            'player_one' => $playerRound->id,
                            'player_two' => $game->getWinner()->id,
                            'tournament_id' => $this->tournament->id,
                            'match_number' => $matchesNumber
                        ]);
                    $playerRound = null;
                    $singleNotWinner = $this->tournament->singleMatches()->whereNull('winner_id')->get();
                    continue;
                }
                $playerRound = $game->getWinner();
                $totalPlayed++; // 8 // 4 // 2 // 1
            }
        }

    }




}
