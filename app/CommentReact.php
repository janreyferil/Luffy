<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentReact extends Model
{
    public function comment(){
        return $this->belongsTo(PostComment::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
