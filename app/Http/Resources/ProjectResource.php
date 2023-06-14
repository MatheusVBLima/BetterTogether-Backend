<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ExperienceCollection;
use App\Http\Resources\ExperienceResource;

class ProjectResource extends JsonResource
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
            "id" => $this -> id,
            "name" => $this -> name,
            "description" => $this -> description,
            "contact" => $this -> contact,
            "user" => [
                "name" => $this -> user -> name,
                "email" => $this -> user -> email,
                "user_id" => $this -> user -> id,
            ],
            "experiences" => ExperienceResource::collection($this -> experiences),
         ] ;
    }
}
