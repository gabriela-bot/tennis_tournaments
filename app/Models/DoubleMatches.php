<?php

namespace App\Models;

use App\Interfaces\Attributes;
use App\Interfaces\Players;
use App\Interfaces\Sides;
use App\Traits\HasMatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoubleMatches extends Model implements Sides, Players
{
    /** @use HasFactory<\Database\Factories\DoubleMatchesFactory> */
    use HasFactory;
    use HasMatch;
    protected $fillable = [
        'group_one',
        'group_two',
        'tournament_id',
        'group_winner_id',
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
    public function groupOne(){
        return $this->belongsTo(Groups::class, 'group_one');
    }
    public function groupTwo(){
        return $this->belongsTo(Groups::class, 'group_two');
    }

    public function winner()
    {
        return $this->belongsTo(Groups::class, 'group_winner_id');
    }

    public function secondPlayer()
    {
        $winner = $this->group_winner_id;
        if($winner == $this->group_one){
            return $this->belongsTo(Groups::class, 'group_two');
        }
        return $this->belongsTo(Groups::class, 'group_one');
    }


    public function sideA()
    {
        return $this->belongsTo(Groups::class, 'group_one');
    }

    public function sideB()
    {
        return $this->belongsTo(Groups::class, 'group_two');
    }

    public function classOfPlayer(): string
    {
        return Groups::class;
    }
}
