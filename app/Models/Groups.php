<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    /** @use HasFactory<\Database\Factories\GroupsFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'player_one',
        'player_two',
    ];
}
