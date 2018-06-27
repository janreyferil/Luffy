<?php

namespace App\Http\Resources\Primary;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Foreign\UsersResource as FUR;
use App\Http\Resources\Foreign\PostCommentsResource as FPCR;

class CommentReact extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
     //   return parent::toArray($request);
     return [
        "user" => new FUR($this->user),
        "comment" => new FPCR($this->comment),
        "like" => $this->like,
        "dislike" => $this->dislike,
        "updated_at" => $this->updated_at->diffForHumans()
    ];
    }
}
