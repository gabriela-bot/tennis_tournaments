<?php

namespace App\Http\Resources\Matches;

use App\Http\Resources\Tournament\TournamentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SingleMatchesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => SingleMatchesResponse::collection($this->collection)
        ];
    }
}
