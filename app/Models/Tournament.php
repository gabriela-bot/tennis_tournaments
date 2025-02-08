<?php

namespace App\Models;

use App\Casts\CategoryCast;
use App\Casts\StatusCast;
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


}
