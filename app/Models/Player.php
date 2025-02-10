<?php

namespace App\Models;

use App\Casts\CategoryCast;
use App\Interfaces\Attributes;
use App\Traits\HasHabilitates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Player extends Model implements Attributes
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;
    use HasHabilitates;
    protected $fillable = [
        'category',
        'name',
        'level',
        'power',
        'speed',
        'reaction'
    ];

    protected $casts = [
        'category' => CategoryCast::class,
    ];

    public function matchesPlayerOne(){
        return $this->hasMany(SingleMatches::class, 'player_one');
    }

    public function matchesPlayerTwo(){
        return $this->hasMany(SingleMatches::class, 'player_two');
    }

    public function groupOne()
    {
        return $this->hasMany(Groups::class, 'player_one');
    }

    public function groupTwo()
    {
        return $this->hasMany(Groups::class, 'player_two');
    }

    public function shortenName() : Attribute
    {
        return Attribute::make(
            get: function(){
                $name = explode(' ',$this->name);
                return Arr::first($name) . ' ' .Str::upper( substr(Arr::last($name),0,1) ).'.';
            }
        );
    }

    public function getLevel(): int
    {
        return $this->level ?? 0;
    }

    public function getReaction() : int
    {
        return $this->reaction ?? 0;
    }

    public function getPower() : int
    {
        return $this->power ?? 0;
    }

    public function getSpeed() : int
    {
        return $this->speed ?? 0;
    }
}
