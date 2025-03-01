<?php

namespace App\Repositories;

use App\Enum\Category;

class Attributes
{
    public int $level = 0;
    public int $power = 0;
    public int $speed = 0;
    public int $reaction = 0;

    public function __construct(int $level, $category)
    {
        $this->level = $level;
        $this->generateAttributes($category);
    }

    public function generateAttributes($category) : void
    {
        if($category instanceof Category){
            $category = $category->value;
        }
        if($category == 'women'){
            $this->reaction = rand(70, 100);
            $this->speed = 0;
            $this->power =  0;
        }
        if($category == 'men'){
            $this->power = rand(30, 100);
            $this->speed = rand(30, 100);
            $this->reaction = 0;
        }
        if(($this->reaction + $this->power + $this->speed) > 100) {
            $this->generateAttributes($category);
        }
    }

    public function getReaction(): int
    {
        return $this->reaction;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function values() : array
    {
        return [
            'level' => $this->level,
            'power' => $this->power,
            'speed' => $this->speed,
            'reaction' => $this->reaction,
        ];
    }

}
