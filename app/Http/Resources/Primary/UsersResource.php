<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
       return [
        "id" => $this->id,
        "first" => $this->first,
        "last" => $this->last,
        "email" => $this->email,
        "created_at" => $this->created_at->diffForHumans()
     ];
    }
}
