<?php

namespace App\Http\Controllers\Tournament;

use App\Enum\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tournament\CreateTournamentRequest;
use App\Http\Resources\Tournament\TournamentCollection;
use App\Http\Resources\Tournament\TournamentResource;
use App\Models\Tournament;
use App\Services\TournamentService;

class TournamentController extends Controller
{
    public TournamentService $createService;

    public function __construct(TournamentService $createService)
    {
        $this->createService = $createService;
    }

    public function index()
    {
        $tournamentList = $this->createService->allTournaments();
        $collection = (new TournamentCollection($tournamentList));
        return $collection->preserveQuery();
    }

    public function store(CreateTournamentRequest $request){
        try {
            $request['status'] = $request['status'] ?? Status::PENDING->value;

            $tournament = $this->createService->createTournament($request->only(
                [
                    'category',
                    'name',
                    'date',
                    'players',
                    'status',
                    'team'
                ]
            ));
            return new TournamentResource($tournament);
        } catch (\Exception $e){
            return [
                'message' => $e->getMessage(),
            ];
        }
    }


    public function show(Tournament $tournament) {
       $this->createService->tournament = $tournament;

        return$this->createService->returnTournament();
    }

    public function play(Tournament $tournament){
        $tournament->update([
            'status' => Status::ACTIVE->value
        ]);
       $this->createService->tournament = $tournament;
       $this->createService->playMatches();
        return$this->createService->returnTournament();
    }

    public function createAndPlay(CreateTournamentRequest $request)
    {
        $request['status'] = $request['status'] ?? Status::ACTIVE->value;
       $this->createService->createTournamentAndPlay($request->only(
            [
                'category',
                'name',
                'date',
                'players',
                'status',
                'team'
            ]
        ));
        return$this->createService->returnTournament();
    }


}
