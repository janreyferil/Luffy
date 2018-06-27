<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'body'
    ];

    public function user_post_comments(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function post_comments(){
        return $this->belongsTo(Post::class,'post_id');
    }

    public function postcomment_reacts(){
        return $this->hasMany(CommentReact::class,'comment_id');
    }
}
