<?php

namespace App\Models;

use App\Casts\CategoryCast;
use App\Casts\StatusCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentFactory> */
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'date',
        'players',
        'status',
        'team'
    ];

    protected $casts = [
        'category' => CategoryCast::class,
        'status' => StatusCast::class
    ];

    public function singleMatches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SingleMatches::class);
    }

    public function doubleMatches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoubleMatches::class);
    }

    public function winner() : Attribute
    {
        return Attribute::make(
            get: function(){
                if($this->team){
                   $last = $this->doubleMatches()->whereNotNull('group_winner_id')->orderBy('id', 'DESC')->first();
                   if($last){
                       return optional($last->winner)->nameGroup;
                   }
                } $last = $this->singleMatches()->whereNotNull('winner_id')->orderBy('id', 'DESC')->first();
                if($last){
                    return optional($last->winner)->shortenName;
                }
                return;
            }
        );

    }

    public function secondPlayer() : Attribute
    {
        return Attribute::make(
            get: function(){
                if($this->team){
                    $last = $this->doubleMatches()->whereNotNull('group_winner_id')->orderBy('id', 'DESC')->first();
                    if($last){
                        return optional($last->secondPlayer)->nameGroup;
                    }
                } $last = $this->singleMatches()->whereNotNull('winner_id')->orderBy('id', 'DESC')->first();
                if($last){
                    return optional($last->secondPlayer)->shortenName;
                }
                return;
            }
        );

    }



}
