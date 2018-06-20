<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Foreign\UsersResource as FUR;
use App\Http\Resources\Foreign\PostReactsResource as FPRR;
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
      //  return parent::toArray($request);
      return [
        "id" => $this->id,
        "user" => new FUR($this->user),
        "title" => $this->title,
        "body" => $this->body,
        "react" => [
            "like" => $this->post_reacts == null ? 0 : $this->post_reacts->sum('like'),
            "dislike" =>  $this->post_reacts == null ? 0 : $this->post_reacts->sum('dislike'),
         ],
        "created_at" => $this->created_at->diffForHumans(),
        "updated_at" => $this->updated_at->diffForHumans()
        ];
    }
}
