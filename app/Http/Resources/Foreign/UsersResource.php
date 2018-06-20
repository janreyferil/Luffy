<?php

namespace App\Http\Resources\Foreign;

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
        "first" => $this->first,
        "last" => $this->last,
        "email" => $this->email,
     ];
    }
}
