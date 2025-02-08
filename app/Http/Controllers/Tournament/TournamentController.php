<?php

namespace App\Http\Controllers\Tournament;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tournament\CreateTournamentRequest;
use App\Models\Tournament;
use App\Services\TournamentService;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public TournamentService $service;
    public function __construct(TournamentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {

    }

    public function store(CreateTournamentRequest $request){
        $request['status'] = $request['status'] ?? Status::PENDING->value;
        $tournament = $this->service->createTournament($request->only(
            [
                'category',
                'name',
                'date',
                'players',
                'status',
                'team'
            ]
        ));
        return $tournament;
    }

    public function play(Tournament $tournament){
        $tournament->update([
            'status' => Status::ACTIVE->value
        ]);
        $this->service->tournament = $tournament;
        $this->service->createMatches();
        if($tournament->team){

        } else {
            $lastMarch = $tournament->singleMatches()->whereNotNull('winner_id')->orderBY('id', 'DESC')->with([
                'winner',
                'secondPlayer'
            ])->first();
            return [
                'tournament' => $tournament,
                'lastMarch' => $lastMarch,
                'message' => 'Felicidades ' . $lastMarch->winner->name . ' ha ganado el juego.',
            ];
        }
    }

    public function createAndPlay(CreateTournamentRequest $request)
    {
        $request['status'] = $request['status'] ?? Status::ACTIVE->value;
        $tournament = $this->service->createTournamentAndPlay($request->only(
            [
                'category',
                'name',
                'date',
                'players',
                'status',
                'team'
            ]
        ));


        if($tournament->team){

        } else {
            $lastMarch = $tournament->singleMatches()->whereNotNull('winner_id')->orderBY('id', 'DESC')->with([
                'winner',
                'secondPlayer'
            ])->first();
            return [
                'tournament' => $tournament,
                'lastMarch' => $lastMarch,
                'message' => 'Felicidades ' . $lastMarch->winner->name . ' ha ganado el juego.',
            ];
        }
    }

    public function show(Tournament $tournament)
    {

    }

    public function update(Tournament $tournament,Request $request)
    {

    }
}
