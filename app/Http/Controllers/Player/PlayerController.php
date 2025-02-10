<?php

namespace App\Http\Controllers\Player;

use App\Enum\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Player\PlayPlayerRequest;
use App\Http\Resources\Players\PlayerCollection;
use App\Models\Player;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    public TournamentService $service;
    public function __construct(TournamentService $service)
    {
        $this->service = $service;
    }

    public function index(){
        $request = request();

        $parameter = $request->only([
            'name',
            'category',
            'level',
            'reaction',
            'power',
            'speed'
        ]);

        $paginate = $request->get('paginate', false);
        $order = $request->get('order', 'id');
        $sort = $request->get('sort', 'asc');

        $query = Player::where(
            function ($query) use ($parameter) {
                foreach ($parameter as $key => $value){
                    if($key == 'name'){
                        $query->where($key, 'LIKE','%'.$value. '%');
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        )->orderBy($order, $sort);
        if($paginate){
            $playerList = $query->paginate();
        } else {
            $playerList = $query->get();
        }

        $collection = (new PlayerCollection($playerList));
        return $collection->preserveQuery();

    }

    public function play(PlayPlayerRequest $request){

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
