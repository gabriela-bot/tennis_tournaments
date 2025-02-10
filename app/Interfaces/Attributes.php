<?php

namespace App\Interfaces;

interface Attributes
{
    public function getLevel():int;

    public function getReaction():int;

    public function getPower():int;

    public function getSpeed():int;

    public function getSkillsScore():int;

}
