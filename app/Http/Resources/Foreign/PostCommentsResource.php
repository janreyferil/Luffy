<?php

namespace App\Http\Resources\Foreign;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentsResource extends JsonResource
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
        "user_comment" => new UsersResource($this->user_post_comments),
        "user_post" => new PostsResource($this->post_comments),
        "body" => $this->body,
        "created_at" => $this->created_at->diffForHumans(),
        "updated_at" => $this->updated_at->diffForHumans()
      ];
    }
}
