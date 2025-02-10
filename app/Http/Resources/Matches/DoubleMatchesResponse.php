<?php

namespace App\Http\Resources\Matches;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoubleMatchesResponse extends JsonResource
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
            "group_one" => $this->groupOne->nameGroup,
            "group_two" => $this->groupTwo->nameGroup,
            "group_winner" => $this->winner->nameGroup,
            "winner_set_one"=> $this->winnerSetOne->nameGroup,
            "set_one"=> $this->set_one,
            "winner_set_two"=> $this->winnerSetTwo->nameGroup,
            "set_two"=> $this->set_two,
            "winner_set_three"=> $this->when($this->winner_set_three, optional($this->winnerSetThree)->nameGroup),
            "set_three"=> $this->when($this->set_three, $this->set_three),
        ];
    }
}
