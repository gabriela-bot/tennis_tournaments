<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoubleMatches extends Model
{
    /** @use HasFactory<\Database\Factories\DoubleMatchesFactory> */
    use HasFactory;

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

}
