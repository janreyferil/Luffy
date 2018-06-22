<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\PostComment;
use App\Http\Resources\Primary\PostsResource as PR;
use App\Http\Requests\PostsRequest;
class PostsController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = PR::collection(Post::orderBy('created_at','DESC')->paginate(5));
        return response()->json($posts,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsRequest $request)
    {
        $post = Post::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'body' => $request->body
        ]);
        $post->save();
        return response()->json([
            "post" => new PR($post),
            "success" => true,
            "message" => "Post Added"
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json(new PR($post),200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostsRequest $request, Post $post)
    {
        if(auth()->user()->id !== $post->user_id){
            return response()->json([
                'restrict' => true,
                'message' => 'You cannot change this post'
            ],200);
        }
        $post->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "Post Updated"
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(auth()->user()->id !== $post->user_id){
            return response()->json([
                'errors' => true,
                'message' => 'You cannot change this post'
            ],200);
        }
        $comments = PostComment::where('post_id', $post->id)->get();
        if(!$comments->isEmpty()){
            foreach($comments as $comment){
                $comment->delete();
            }
        }
        $post->delete();
        return response()->json([
            "success" => true,
            "message" => "Post Deleted"
        ],200);
    }
}
