<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostReact;
use App\CommentReact;
use App\PostComment;
use App\Post;
class ReactsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware('isuser');
    }

    public function post_react(Request $request,Post $post){
        $react = PostReact::where('post_id',$post->id)->first();
        if($request->react === 'like'){
            if($react === null){
                $new = new PostReact;
                $new->user_id = auth()->user()->id;
                $new->post_id = $post->id;
                $new->like = 1;
                $new->dislike = 0;
                $new->save();
                return response()->json($new,200);
            } elseif($react->like == 0 && $react->dislike == 0){
                $react->like = 1;
            }
            elseif($react->like == 1 && $react->dislike == 0){
                $react->like = 0;
            } elseif($react->like == 0 && $react->dislike == 1){
                $react->like = 1;
                $react->dislike = 0;
            }
        } elseif($request->react === 'dislike'){
            if($react === null){
                $new = new PostReact;
                $new->user_id = auth()->user()->id;
                $new->post_id = $post->id;
                $new->like = 0;
                $new->dislike = 1;
                $new->save();
                return response()->json($new,200);
            } elseif($react->dislike == 0 && $react->like == 0){
                $react->dislike = 1;
            }
            elseif($react->dislike == 1 && $react->like == 0){
                $react->dislike = 0;
            } elseif($react->dislike == 0 && $react->like == 1){
                $react->dislike = 1;
                $react->like = 0;
            }
        }
        $react->save();
        return response()->json($react,200);
    }

    public function comment_react(Request $request,Post $post,PostComment $comment){
        $react = CommentReact::where('comment_id',$comment->id)->first();
        if($request->react === 'like'){
            if($react === null){
                $new = new CommentReact;
                $new->user_id = auth()->user()->id;
                $new->comment_id = $comment->id;
                $new->like = 1;
                $new->dislike = 0;
                $new->save();
                return response()->json($new,200);
            } elseif($react->like == 0 && $react->dislike == 0){
                $react->like = 1;
            }
            elseif($react->like == 1 && $react->dislike == 0){
                $react->like = 0;
            } elseif($react->like == 0 && $react->dislike == 1){
                $react->like = 1;
                $react->dislike = 0;
            }
        } elseif($request->react === 'dislike'){
            if($react === null){
                $new = new CommentReact;
                $new->user_id = auth()->user()->id;
                $new->comment_id = $comment->id;
                $new->like = 0;
                $new->dislike = 1;
                $new->save();
                return response()->json($new,200);
            } elseif($react->dislike == 0 && $react->like == 0){
                $react->dislike = 1;
            }
            elseif($react->dislike == 1 && $react->like == 0){
                $react->dislike = 0;
            } elseif($react->dislike == 0 && $react->like == 1){
                $react->dislike = 1;
                $react->like = 0;
            }
        }
        $react->save();
        return response()->json($react,200);
    }
}
