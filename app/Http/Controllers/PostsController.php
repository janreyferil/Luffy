<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\PostComment;
use App\Http\Resources\Primary\Post as P;
use App\Http\Resources\Primary\PostCollection as PC;
use App\Http\Requests\PostsRequest;
use Illuminate\Support\Facades\Storage;

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
        $posts = Post::orderBy('created_at','DESC')->paginate(5);
        $collection = new PC($posts);
        return $collection;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsRequest $request)
    {
        if($request->image != '') {
            $exploded = explode(',',$request->image);
        
            $decoded = base64_decode($exploded[1]);
            
            if(str_contains($exploded[0],'jpeg'))
             $extension = 'jpg';
            else 
             $extension = 'png';

            $fileName = str_random().'.'.$extension;

            $path = public_path().'/storage/image/'.$fileName;

            file_put_contents($path,$decoded);
         } else {
                $fileName = 'noimage.jpg';
        }

        $post = Post::create($request->except('image') + [
            'user_id' => auth()->user()->id,
            'image' => $fileName
        ]);
        
        $post->save();
        return response()->json([
            "post" => new P($post),
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
        return response()->json(new P($post),200);
    }
    /**
     * Update the specified resource in storage.
     *hv y
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if(auth()->user()->id !== $post->user_id){
            return response()->json([
                'restrict' => true,
                'message' => 'You cannot change this post'
            ],200);
        }

        if($request->image != '') {
  
            if($post->image != 'noimage.jpg')
            {
                Storage::delete('public/image/'.$post->image);
            }
      
            $exploded = explode(',',$request->image);
        
            $decoded = base64_decode($exploded[1]);

            if(str_contains($exploded[0],'jpeg'))
             $extension = 'jpg';
            else 
             $extension = 'png';

            $fileName = str_random().'.'.$extension;

            $path = public_path().'/storage/image/'.$fileName;
            
            file_put_contents($path,$decoded);
         } else {
                $fileName = $post->image;
        }

        $post->title = $request->title == '' ? $post->title : $request->title;
        $post->title = $request->body == '' ? $post->body : $request->body;
        $post->image = $fileName;
        $post->save();

        $post->update($request->except('image'),[
            'image' => $fileName
        ]);

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
        if($post->image != 'noimage.jpg')
        {
            Storage::delete('public/image/'.$post->image);
        }
        $post->delete();
        return response()->json([
            "success" => true,
            "message" => "Post Deleted"
        ],200);
    }
}
