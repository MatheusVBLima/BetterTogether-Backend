<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\CandidateResource;

class UserMeProjectsResource extends JsonResource
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
            "experiences" => ExperienceResource::collection($this -> experiences),
            "candidates" => $this -> experiences->map(function($experience) {
                $candidates = $experience->users->where('id', '!=', $this ->user_id);
            return CandidateResource::collection($candidates);
            })->flatten()->unique('id')
         ] ;

    }
}
