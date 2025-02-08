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
        Route::post('/', 'store');
        Route::post('/{tournament}/play', 'play');
        Route::post('/new-play', 'createAndPlay');
        Route::get('/{tournament}', 'show');
        Route::patch('/{tournament}', 'update');
    });

Route::controller(PlayerController::class)
    ->prefix('player')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::patch('/{player}', 'update');
    });

Route::controller(MatchesController::class)
    ->prefix('matches')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{match}', 'show');
    });

Route::controller(WinnerController::class)
    ->prefix('winner')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{winner}', 'show');
        Route::get('/certification/{winner}', 'certification');
    });
