<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'body','image'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function post_comments(){
        return $this->hasMany(PostComment::class)->orderBy('created_at','DESC');
    }

    public function post_reacts(){
        return $this->hasMany(PostReact::class);
    }

    public function comment_reacts(){
        return $this->hasMany(CommentReact::class);
    }
}
