<?php

namespace App\Http\Controllers\Player;

use App\Enum\Status;
use App\Exceptions\InvalidRequestApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Player\PlayPlayerRequest;
use App\Http\Requests\Player\PlayWithoutPlayersRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\Players\PlayerCollection;
use App\Models\Player;
use App\Models\Tournament;
use App\Services\PlayerServices;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlayerController extends Controller
{

    public TournamentService $service;
    public PlayerServices $servicePlayer;
    public function __construct(TournamentService $service, PlayerServices $servicePlayer)
    {
        $this->service = $service;
        $this->servicePlayer = $servicePlayer;
    }

    public function index(){
        try{
            return $this->servicePlayer->allPlayer();
        }  catch (InvalidRequestApiException $e){
            return $e->render(request());
        } catch (\Exception $e){
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }

    public function playWithPlayers(PlayPlayerRequest $request){

        try{
            $request['status'] = $request['status'] ?? Status::PENDING->value;
            $attributes = $request->only(
                [
                    'category',
                    'name',
                    'date',
                    'status',
                ]
            );
            $attributes['players'] = count($request['players']);
            $attributes['team'] = 0;
            $tournament = $this->service->newTournament($attributes);
            $tournament->update([
                'status' => Status::ACTIVE->value
            ]);
            $this->service->createMatchesForPlayer($request->players);
            $this->service->playMatches();
            return $this->service->returnTournament();
        }  catch (InvalidRequestApiException $e){
            return $e->render(request());
        } catch (\Exception $e){
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }

    public function playWithoutPlayers(PlayWithoutPlayersRequest $request){
        try {

            $playersId = $this->servicePlayer->createPlayer($request->players, $request->category);

            $request['status'] = $request['status'] ?? Status::PENDING->value;
            $attributes['players'] = count($playersId);

            $attributes = $request->only(
                [
                    'category',
                    'name',
                    'date',
                    'status',
                ]
            );
            $tournament = $this->service->newTournament($attributes);
            $tournament->update([
                'status' => Status::ACTIVE->value
            ]);
            $this->service->createMatchesForPlayer($playersId);
            $this->service->playMatches();
            return $this->service->returnTournament();

        }  catch (InvalidRequestApiException $e){
            return $e->render(request());
        } catch (\Exception $e){
            return (new ErrorResource($e))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }

    public function random() : array|string
    {
        $total = request()->get('total', 2);
        $players = Player::where('category', request()->get('category'))->pluck('id')->toArray();
        if($total > count($players)) return ['message' => 'No hay suficientes players'];
        shuffle($players);
        return [ 'players' => array_slice($players, 0, $total), 'total' => $total];
    }
}
