<?php

namespace App\Services;

use App\Helpers\RandomGroupGenerator;
use App\Models\SingleMatches;
use Illuminate\Support\Facades\Log;
use Nette\Utils\Random;

class Matches
{

    public $tournament;

    /**
     * @param $tournament
     */
    public function __construct($tournament)
    {
        $this->tournament = $tournament;
    }

    public function double()
    {

    }

    public function simple()
    {
        $singleNotWinner = $this->tournament->singleMatches()->whereNull('winner_id')->get();
        $totalMatches = $this->tournament->players/2;
        $totalPlayed = 0;
        while ($totalPlayed != $totalMatches) {
            $playerRound = null;
            foreach ($singleNotWinner as $single) {
                $game = new Winners($single);
                $game->start();
                $single->update([
                    'winner_id' => $game->getWinner()->id,
                ]);
                if(!is_null($playerRound)) {
                    SingleMatches::create([
                            'player_one' => $playerRound->id,
                            'player_two' => $game->getWinner()->id,
                            'tournament_id' => $this->tournament->id
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
