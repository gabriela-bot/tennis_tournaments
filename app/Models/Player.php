<?php

namespace App\Models;

use App\Casts\CategoryCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;


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

    public function simpleMatches(){
        return $this->hasMany(SingleMatches::class, 'player_one')->orWhere('player_two', $this->id);
    }
}
