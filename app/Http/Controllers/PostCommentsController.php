<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\PostComment;
use App\Http\Resources\Primary\PostCommentCollection as PCC;
use App\Http\Resources\Primary\PostComment as PCR;
use App\Http\Requests\PostCommentsRequest;
class PostCommentsController extends Controller
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
    public function index(Post $post)
    {
        $comments = $post->post_comments()->orderBy('created_at','DESC')->paginate(5);
        $collection = new PCC($comments);
        return $collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCommentsRequest $request,Post $post)
    {
        $comment = PostComment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'body' => $request->body,
        ]);
        $post->post_comments()->save($comment);
        return response()->json([
            "success" => true,
            "message" => "Comment Added",
            "comment" => new PCR($comment)
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, PostComment $comment)
    {
        $comments = new PCR($comment);
        return response()->json($comments,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostCommentsRequest $request, Post $post,PostComment $comment)
    {
        if(auth()->user()->id !== $comment->user_id){
            return response()->json([
                'errors' => true,
                'message' => 'You cannot change this comment'
            ],200);
        }
        $comment->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "Comment Added",
            "comment" => new PCR($comment)
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, PostComment $comment)
    {
        if(auth()->user()->id !== $comment->user_id){
            return response()->json([
                'errors' => true,
                'message' => 'You cannot change this comment'
            ],200);
        }
        $comment->delete();
        return response([
            'data' => null
        ],204);
    }
}
