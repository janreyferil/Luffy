<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Foreign\UsersResource as FUR;
use App\Http\Resources\Foreign\PostsResource as FPR;
use App\Http\Resources\Foreign\CommentReactsResource as FCRR;
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
        "id" => $this->id,
        "user_id" => new FUR($this->user_post_comments),
        "post_id" => new FPR($this->post_comments),
        "body" => $this->body,
        "react" => [
            "like" => $this->comment_reacts == null ? 0 : $this->comment_reacts->sum('like'),
            "dislike" =>  $this->comment_reacts == null ? 0 : $this->comment_reacts->sum('dislike'),
         ],
        "created_at" => $this->created_at->diffForHumans(),
        "updated_at" => $this->updated_at->diffForHumans()
    ];
    }
}