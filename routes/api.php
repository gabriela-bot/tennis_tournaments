<?php


use App\Exceptions\InvalidRequestApiException;
use App\Http\Controllers\Matches\MatchesController;
use App\Http\Controllers\Player\PlayerController;
use App\Http\Controllers\Tournament\TournamentController;
use App\Http\Controllers\Winners\WinnerController;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::controller(TournamentController::class)
    ->prefix('tournament')
    ->name('tournament.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{tournament}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::post('/{tournament}/play', 'play')->name('play');
        Route::post('/new-play', 'createAndPlay')->name('new-play');
    });

Route::controller(PlayerController::class)
    ->prefix('player')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/random', 'random');
        Route::post('/tournament', 'playWithPlayers');
        Route::post('/tournament/new-player', 'playWithoutPlayers');
    });

