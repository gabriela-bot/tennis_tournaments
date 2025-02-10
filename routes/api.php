<?php


use App\Http\Controllers\Matches\MatchesController;
use App\Http\Controllers\Player\PlayerController;
use App\Http\Controllers\Tournament\TournamentController;
use App\Http\Controllers\Winners\WinnerController;
use Illuminate\Support\Facades\Route;

Route::controller(TournamentController::class)
    ->prefix('tournament')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{tournament}', 'show');
        Route::post('/', 'store');
        Route::post('/{tournament}/play', 'play');
        Route::post('/new-play', 'createAndPlay');
    });

Route::controller(PlayerController::class)
    ->prefix('player')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/random', 'random');
        Route::post('/tournament', 'play');
    });

