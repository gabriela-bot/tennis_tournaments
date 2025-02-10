<?php

namespace App\Http\Resources\Tournament;

use App\Enum\Status;
use App\Http\Resources\Matches\DoubleMatchesResponse;
use App\Http\Resources\Matches\SingleMatchesResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{

    public static $wrap = 'tournament';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'name' => $this->name,
            'category' => $this->category,
            'date' => $this->date,
            'status' => $this->status,
            'team' =>  $this->team,
            'players' => $this->players,
            'matches_player' => $this->team ?
                 $this->doubleMatches()->whereNotNull('group_winner_id')->count()
                : $this->singleMatches()->whereNotNull('winner_id')->count(),
            $this->mergeWhen(!$this->team && $this->status == Status::FINISH, [
                'winner_player' => $this->winner,
                'second_player' => $this->secondPlayer
            ]),
            $this->mergeWhen($this->team && $this->status == Status::FINISH, [
                'winner_group' => $this->winner,
                'second_group' => $this->secondPlayer,
            ]),
        ];

    }


}
