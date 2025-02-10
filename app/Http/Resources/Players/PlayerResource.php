<?php

namespace App\Http\Resources\Players;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'level' => $this->getLevel(),
            'reaction' => $this->getReaction(),
            'power' => $this->getPower(),
            'speed' => $this->getSpeed(),
            'category' => $this->category,
            'id' => $this->id
        ];
    }
}
