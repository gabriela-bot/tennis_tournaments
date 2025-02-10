<?php

namespace App\Models;

use App\Interfaces\Attributes;
use App\Traits\HasHabilitates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Groups extends Model implements Attributes
{
    /** @use HasFactory<\Database\Factories\GroupsFactory> */
    use HasFactory;
    use HasHabilitates;
    protected $fillable = [
        'name',
        'player_one',
        'player_two',
    ];

    public function playerOne(){
        return $this->belongsTo(Player::class, 'player_one');
    }
    public function playerTwo(){
        return $this->belongsTo(Player::class, 'player_two');
    }

    public function doubleMatchesOne(){
        return $this->hasMany(DoubleMatches::class, 'group_one');
    }
    public function doubleMatchesTwo(){
        return $this->hasMany(DoubleMatches::class, 'group_two');
    }

    public function nameGroup() : Attribute
    {
        return Attribute::make(
            get: fn () => $this->playerOne->shortenName . ' & '.$this->playerTwo->shortenName . ' - ' .$this->name ,
        );
    }

    public function getLevel(): int
    {
        $playerOne = $this->belongsTo(Player::class, 'player_one')->first();
        $playerTwo = $this->belongsTo(Player::class, 'player_two')->first();

        return $playerOne->level + $playerTwo->level;
    }

    public function getReaction(): int
    {
        $playerOne = $this->belongsTo(Player::class, 'player_one')->first();
        $playerTwo = $this->belongsTo(Player::class, 'player_two')->first();

        return $playerOne->reaction + $playerTwo->reaction;
    }

    public function getPower(): int
    {
        $playerOne = $this->belongsTo(Player::class, 'player_one')->first();
        $playerTwo = $this->belongsTo(Player::class, 'player_two')->first();

        return $playerOne->power + $playerTwo->power;
    }

    public function getCategory()
    {
        $playerOne = $this->belongsTo(Player::class, 'player_one')->first();
        return $playerOne->category;
    }

    public function getSpeed(): int
    {
        $playerOne = $this->belongsTo(Player::class, 'player_one')->first();
        $playerTwo = $this->belongsTo(Player::class, 'player_two')->first();

        return $playerOne->speed + $playerTwo->speed;
    }

}
