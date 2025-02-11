<?php

namespace App\Services;

use App\Exceptions\InvalidRequestApiException;
use App\Http\Resources\Players\PlayerCollection;
use App\Models\Player;
use App\Repositories\Attributes;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class PlayerServices
{
    public function allPlayer(Request $request = null): PlayerCollection
    {
        if(!$request) $request = request();

        $parameter = $request->only([
            'name',
            'category',
            'level',
            'reaction',
            'power',
            'speed'
        ]);

        $paginate = $request->get('paginate', false);

        if(!in_array($paginate, [1,0])){
            throw new InvalidRequestApiException('Invalid paginate parameters.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $order =Str::lower( $request->get('order', 'id'));

        if(!in_array($order, ['name', 'category', 'level', 'reaction','power','speed','id'])){
            throw new InvalidRequestApiException('Invalid order parameters.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $sort = Str::upper($request->get('sort', 'asc'));

        if(!in_array($sort, ['ASC', 'DESC'])){
            throw new InvalidRequestApiException('Invalid sort parameters.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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

    public function createPlayer(array $players, $category = null){

        $players = collect($players)->map(function($player) use ($category){
            $attributes = [
                'category' => array_key_exists('category', $player) ? $player['level'] : $category,
                'name' => $player['name'],
                'level' => array_key_exists('level', $player) ? $player['level'] : null,
                'power' => array_key_exists('power', $player) ? $player['power'] : null,
                'reaction' => array_key_exists('reaction', $player) ? $player['reaction'] : null,
                'speed' => array_key_exists('speed', $player) ? $player['speed'] : null,
            ];

            if(is_null($attributes['level'])){
                $attributes['level'] = rand(0,100);
            }

            $nullAttributes = array_keys(array_filter([
                'power' => $attributes['power'] ?? null,
                'speed' => $attributes['speed'] ?? null,
                'reaction' => $attributes['reaction'] ?? null
            ], fn($value) => is_null($value)));
            if(!empty($nullAttributes)){
                $skills = new Attributes($attributes['level'],$attributes['category']);
                foreach ($nullAttributes as $value){
                    $function = 'get'.ucfirst($value);
                    $attributes[$value] = $skills->{$function}();
                }
            }
            $newPlayer = Player::create($attributes);
            return $newPlayer->id;
        })->toArray();

        return $players;
    }
}
