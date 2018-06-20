<?php

namespace App\Http\Resources\Foreign;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
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
        "user" => new UsersResource($this->user),
        "title" => $this->title,
        "created_at" => $this->created_at->diffForHumans(),
        "updated_at" => $this->updated_at->diffForHumans()
       ];
    }
}
