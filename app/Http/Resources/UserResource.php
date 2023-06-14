<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ExperienceCollection;
use App\Http\Resources\ExperienceResource;
class UserResource extends JsonResource
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
            "id" =>$this -> id,
            "name" => $this -> name,
            "email" => $this -> email,
            "projects" => $this -> projects,
            "experiences" => ExperienceResource::collection($this -> experiences),

         ] ;
    }
}
