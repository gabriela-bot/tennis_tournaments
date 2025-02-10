<?php

namespace App\Traits;

use App\Interfaces\Players;
use App\Models\Player;

trait HasMatch
{
    public function winnerSetOne(){

        if (!$this instanceof Players) {
            throw new \Exception("Se debe implementar la Interfaces Players");
        }

        return $this->belongsTo($this->classOfPlayer(), 'winner_set_one');
    }

    public function winnerSetTwo(){

        if (!$this instanceof Players) {
            throw new \Exception("Se debe implementar la Interfaces Players");
        }

        return $this->belongsTo($this->classOfPlayer(), 'winner_set_two');
    }

    public function winnerSetThree(){

        if (!$this instanceof Players) {
            throw new \Exception("Se debe implementar la Interfaces Players");
        }

        return $this->belongsTo($this->classOfPlayer(), 'winner_set_three');
    }



}
