<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Foreign\UsersResource as FUR;
use App\Http\Resources\Foreign\PostsResource as FPR;

class PostComment extends JsonResource
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
        "user_id" => new FUR($this->user_post_comments),
        "post_id" => new FPR($this->post_comments),
        "body" => $this->body,
        "react" => [
            "like" => $this->postcomment_reacts == null ? 0 : $this->postcomment_reacts->sum('like'),
            "dislike" =>  $this->postcomment_reacts == null ? 0 : $this->postcomment_reacts->sum('dislike'),
         ],
        "created_at" => $this->created_at->diffForHumans(),
        "updated_at" => $this->updated_at->diffForHumans()
     ];
    }
}
