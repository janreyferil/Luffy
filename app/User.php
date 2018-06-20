<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first', 'last', 'role', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function status(){
        return $this->hasOne(Status::class);
    }

    public function reports(){
        return $this->hasMany(Report::class,'user_id');
    }

    public function posts(){
        return $this->belongsTo(Post::class);
    }

    public function user_comments(){
        return $this->hasMany(PostComment::class);
    }

    public function user_post_reacts(){
        return $this->hasMany(PostReact::class);
    }

    public function user_comment_reacts(){
        return $this->hasMany(CommentReact::class);
    }
}
