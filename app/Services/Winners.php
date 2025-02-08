<?php

namespace App\Services;

use App\Models\Player;
use App\Models\SingleMatches;
use Illuminate\Support\Facades\Log;

class Winners
{
    public $game;
    public Player $winner;
    public Player $loser;

    /**
     * @param int $game
     */
    public function __construct($game)
    {
        $this->game = $game;
    }

    public function start() : void
    {
        $playerOne = $this->game->playerOne;
        $playerTwo = $this->game->playerTwo;

        $playerFirst = 0;
        $playerSecond = 0;

        $setPlay = 3;
        for($i = 0 ; $i < $setPlay;$i++){

            $pointsOne = 0;
            $pointsTwo = 0;

            $set = $this->step($i);
            $value = '';


            // Games
            $gameOpen = true;
            while($gameOpen){
                $one = 0;
                $two = 0;

                if($playerOne->level > $playerTwo->level) {
                    $one += 1;
                } else {
                    $two += 1;
                }

                $totalOne = ($playerOne->reaction + $playerOne->power + $playerOne->speed);
                $totalTwo = ($playerTwo->reaction + $playerTwo->power + $playerTwo->speed);

                if($totalOne > $totalTwo) {
                    $one += 1;
                } else {
                    $two += 1;
                }
                if(rand(0,1) == 0) {
                    $one += 1;
                } else {
                    $two += 1;
                }

                if($one > $two) {
                    $pointsOne += 1;
                } else {
                    $pointsTwo += 1;
                }

                $value = $pointsOne.'-'.$pointsTwo;
                if($pointsTwo == 10 || $pointsOne == 10){
                    if($playerOne->level > $playerTwo->level && rand(0,1) == 0) {
                        $pointsOne += 2;
                    } else {
                        $pointsTwo += 2;
                    }

                    $gameOpen = false;
                }
                if(($pointsOne >= 6 || $pointsTwo >= 6) && abs($pointsOne - $pointsTwo) >= 2) $gameOpen = false;

            }

            if($pointsOne > $pointsTwo) {
                $playerFirst += 1;
                $this->game->update([
                    'set_'.$set => $value,
                    'winner_set_'.$set => $playerOne->id,
                ]);
            } else {
                $playerSecond += 1;
                $this->game->update([
                    'set_'.$set => $value,
                    'winner_set_'.$set => $playerTwo->id,
                ]);
            }
            if($playerSecond == 2 || $playerFirst == 2) break;
        }

        if($playerFirst > $playerSecond) {
            $this->winner = $playerOne;
            $this->loser = $playerTwo;
        } else {
            $this->winner = $playerTwo;
            $this->loser = $playerOne;
        }
    }

    public function randValue(): array
    {
        $randOne = rand(0, 50);
        $randTwo = rand(0, 50);

        if($randOne == $randTwo) {
            return $this->randValue();
        }

        return [
            $randOne,
            $randTwo,
        ];
    }

    public function step($i) : string
    {
        return match ($i) {
            0 => 'one',
            1 => 'two',
            2 => 'three'
        };
    }


    public function getWinner() : Player
    {
        return $this->winner;
    }

    public function getLoser() : Player
    {
        return $this->loser;
    }

    private function value(int $one, int $two) : string
    {
        $valueOne = match($one) {
            0 =>  0,
            1 => 15,
            2 => 30,
            3 => 40,
        };

        $valueTwo = match($two) {
            0 =>  0,
            1 => 15,
            2 => 30,
            3 => 40,
        };

        if(!in_array(40, [ $valueOne, $valueTwo ])) {
            $max = max($valueOne, $valueTwo);
            ($max === $valueOne) ? $valueOne = 40 : $valueTwo = 40;
        }
        return $valueOne.'-'.$valueTwo;

    }


}
