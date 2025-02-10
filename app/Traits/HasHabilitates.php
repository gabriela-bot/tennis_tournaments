<?php

namespace App\Traits;

use App\Interfaces\Attributes;

trait HasHabilitates
{
    public function getSkillsScore() : int
    {
        if (!$this instanceof Attributes) {
            throw new \Exception("Se debe implementar la Interfaces Attributes");
        }
        return $this->getPower() + $this->getReaction() + $this->getSpeed();
    }

}
