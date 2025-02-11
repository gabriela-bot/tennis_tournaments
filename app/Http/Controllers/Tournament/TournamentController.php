<?php

namespace App\Http\Controllers\Tournament;

use App\Enum\Status;
use App\Exceptions\InvalidRequestApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tournament\CreateTournamentRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\Tournament\TournamentCollection;
use App\Http\Resources\Tournament\TournamentResource;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\Response;

class TournamentController extends Controller
{
    public TournamentService $createService;

    public function __construct(TournamentService $createService)
    {
        $this->createService = $createService;
    }

    public function index()
    {
        try {
            $tournamentList = $this->createService->allTournaments();
            $collection = (new TournamentCollection($tournamentList));
            return $collection->preserveQuery();
        } catch (InvalidRequestApiException $e){
            return $e->render(request());
        } catch (\Exception $e){
            return (new ErrorResource($e))
                    ->response()
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

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
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }


    public function show($tournament) {
        try {
            $tournament = Tournament::find($tournament);
            if(!$tournament){
                throw new InvalidRequestApiException('Tournament not found', Response::HTTP_NOT_FOUND);
            }
            $this->createService->tournament = $tournament;
            return $this->createService->returnTournament();
        } catch (InvalidRequestApiException $e){
            return $e->render(request());
        } catch (\Exception $e){
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }

    public function play($tournament){
        try {
            $tournament = Tournament::find($tournament);
            if(!$tournament){
                throw new InvalidRequestApiException('Tournament not found', Response::HTTP_NOT_FOUND);
            }
            if($tournament->status == Status::FINISH){
                throw new InvalidRequestApiException('Tournament done', Response::HTTP_CONFLICT);
            }
            $tournament->update([
                'status' => Status::ACTIVE->value
            ]);
            $this->createService->tournament = $tournament;
            $this->createService->playMatches();
            return$this->createService->returnTournament();
        } catch (InvalidRequestApiException $e){
            return $e->render(request());
        } catch (\Exception $e){
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }

    public function createAndPlay(CreateTournamentRequest $request)
    {
        try {
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
        } catch (\Exception $e){
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }


}
