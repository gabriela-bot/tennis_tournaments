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
            "match_number" => $this->match_number,
            "group_one" => $this->groupOne->nameGroup,
            "group_two" => $this->groupTwo->nameGroup,
            "group_winner" => $this->when(!is_null($this->group_winner_id), optional($this->winner)->nameGroup),
            "winner_set_one"=>optional($this->winnerSetOne)->nameGroup,
            "set_one"=> $this->set_one,
            "winner_set_two"=> optional($this->winnerSetTwo)->nameGroup,
            "set_two"=> $this->set_two,
            "winner_set_three"=>  optional($this->winnerSetThree)->nameGroup,
            "set_three"=>$this->set_three,
        ];
    }
}
