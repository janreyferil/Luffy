<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Foreign\UsersResource as FUR;
use App\Http\Resources\Foreign\PostsResource as FPR;

class PostReact extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      //  return parent::toArray($request);
      return [
        "user" => new FUR($this->user),
        "post" => new FPR($this->post),
        "like" => $this->like,
        "dislike" => $this->dislike,
        "updated_at" => $this->updated_at->diffForHumans()
    ];
    }
}
