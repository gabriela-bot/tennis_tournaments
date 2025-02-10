<?php

namespace App\Http\Resources\Matches;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleMatchesResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
             "match_number" =>$this->match_number,
             "player_one" => $this->playerOne->shortenName,
             "player_two" => $this->playerTwo->shortenName,
             "winner" => $this->winner->shortenName,
             "winner_set_one"=> $this->winnerSetOne->shortenName,
             "set_one"=> $this->set_one,
             "winner_set_two"=> $this->winnerSetTwo->shortenName,
             "set_two"=> $this->set_two,
             "winner_set_three"=> $this->when($this->winner_set_three, optional($this->winnerSetThree)->shortenName),
             "set_three"=> $this->whenNotNull($this->set_three),
        ];
    }
}
