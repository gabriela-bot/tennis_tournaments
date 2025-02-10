<?php

namespace App\Repositories;

use App\Models\Groups;
use App\Models\Player;

class Winners
{
    public $match;
    public $winner;
    public $loser;

    public function __construct($match)
    {
        $this->match = $match;
    }

    public function start() : void
    {
        $sideA = $this->match->sideA;
        $sideB = $this->match->sideB;

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

                if($sideA->getLevel() > $sideB->getLevel()) {
                    $one += 1;
                } else {
                    $two += 1;
                }

                $totalOne = $sideA->getSkillsScore();
                $totalTwo = $sideB->getSkillsScore();

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
                    if($sideA->level > $sideB->level && rand(0,1) == 0) {
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
                $this->match->update([
                    'set_'.$set => $value,
                    'winner_set_'.$set => $sideA->id,
                ]);
            } else {
                $playerSecond += 1;
                $this->match->update([
                    'set_'.$set => $value,
                    'winner_set_'.$set => $sideB->id,
                ]);
            }
            if($playerSecond == 2 || $playerFirst == 2) break;
        }

        if($playerFirst > $playerSecond) {
            $this->winner = $sideA;
            $this->loser = $sideB;
        } else {
            $this->winner = $sideB;
            $this->loser = $sideA;
        }
    }

    public function step($i) : string
    {
        return match ($i) {
            0 => 'one',
            1 => 'two',
            2 => 'three'
        };
    }

    public function getWinner() : Player|Groups
    {
        return $this->winner;
    }

    public function getLoser() : Player|Groups
    {
        return $this->loser;
    }


}
