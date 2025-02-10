<?php

namespace App\Http\Resources\Groups;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'players' => $this->nameGroup,
            'level' => $this->getLevel(),
            'reaction' => $this->getReaction(),
            'power' => $this->getPower(),
            'speed' => $this->getSpeed(),
            'category' => $this->getCategory()
        ];
    }
}
