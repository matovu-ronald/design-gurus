<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\DesignResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'total_members' => $this->members->count(),
            'designs' => DesignResource::collection($this->designs),
            'owner' => new UserResource($this->owner),
            'members' => UserResource::collection($this->members),
        ];
    }
}
