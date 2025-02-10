<?php

namespace App\Models;

use App\Interfaces\Players;
use App\Interfaces\Sides;
use App\Traits\HasMatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleMatches extends Model implements Sides, Players
{
    /** @use HasFactory<\Database\Factories\SingleMatchesFactory> */
    use HasFactory;
    use HasMatch;
    protected $fillable = [
        'player_one',
        'player_two',
        'tournament_id',
        'winner_id',
        'set_two',
        'set_three',
        'set_one',
        'winner_set_two',
        'winner_set_three',
        'winner_set_one',
        'match_number'
    ];

    public function tournament(){
        return $this->belongsTo(Tournament::class);
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    public function secondPlayer()
    {
        $winner = $this->winner_id;
        if($winner == $this->player_one){
            return $this->belongsTo(Player::class, 'player_two');
        }
        return $this->belongsTo(Player::class, 'player_one');
    }

    public function playerOne(){
        return $this->belongsTo(Player::class, 'player_one');
    }
    public function playerTwo(){
        return $this->belongsTo(Player::class, 'player_two');
    }

    public function sideA()
    {
        return $this->belongsTo(Player::class, 'player_one');
    }

    public function sideB()
    {
        return $this->belongsTo(Player::class, 'player_two');
    }

    public function classOfPlayer(): string
    {
        return Player::class;
    }
}
