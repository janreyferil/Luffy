<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostReact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'like','dislike'
    ];

   public function post(){
    return $this->belongsTo(Post::class);
   }

   public function user(){
    return $this->belongsTo(User::class);
   }
}
