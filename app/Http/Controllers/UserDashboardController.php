<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use App\User;
use App\Report;
use App\PostReact;
use App\Post;
use App\PostComment;
use App\Http\Requests\ReportsRequest;
use App\Http\Resources\Primary\User as U;
use App\Http\Resources\Primary\Report as R;
use App\Http\Resources\Primary\PostCollection as PC;
use App\Http\Resources\Primary\PostCommentCollection as PCC;
use App\Http\Resources\Primary\PostReactCollection as PRC;
use App\Http\Resources\Primary\CommentReactCollection as CRC;
class UserDashboardController extends Controller
{
    public function __construct(){
       $this->middleware('auth:api');
       $this->middleware('isuser');
    }

    public function home(){
        if(auth()->user()->role == 'admin'){
            $message = "Welcome " . auth()->user()->first . ' ' .  auth()->user()->last;
            $user = auth()->user();
            return response()->json([
                "success" => true,
                "message" => $message,
                "user" => new U($user)
            ],200);
        }
        $status = Status::where(['user_id' => auth()->user()->id,
        'permission' => 'success'])->first();
        $user = auth()->user();
        if($status == null){
            $message = "Welcome " . auth()->user()->first . ' ' .  auth()->user()->last;
        } else {
            $message = "I accepted you in this website and I thank with you for joining here, enjoy here";
            $user = auth()->user();
            $status->permission = 'stable';
            $status->save();
        }
        return response()->json([
            "success" => true,
            "message" => $message,
            "user" => new U($user)
        ],200);
    }

    public function report(ReportsRequest $request){
      $user = User::where('id',$request->user_to_id)->first();
      $message = "You successfuly reported " . $user->first . ' ' . $user->last; 
      $report =  Report::create([
            "user_id" => auth()->user()->id,
            "user_to_id" => $request->user_to_id,
            "report" => $request->report,
            "reason" => $request->reason,
            "level" => $request->level
        ]);
        $report->save();
        return response()->json([
            "success" => true,
            "report" => new R($report),
            "message" => $message
        ],200);
    }

    public function react_history(){
        $user = User::find(auth()->user()->id)->first();
        return response()->json([
            "post_reacts" => new PRC($user->user_post_reacts),
            "comment_reacts" => new CRC($user->user_comment_reacts),
        ],200);
    }

    public function my_activity(){
        $posts = Post::where('user_id',auth()->user()->id)->get();
        $comments = PostComment::where('user_id',auth()->user()->id)->get();
        return response()->json([
            "post" => new PC($posts),
            "comment" => new PCC($comments)
        ],200);
    }

    public function accessControl(Post $post){
          if(auth()->user()->id !== $post->user_id){
              return response()->json([
                  'restrict' => true,
                  'message' => 'You cannot change this post'
              ],200);
          }
          return  response()->json([
              'restrict' => false,
          ],200);
    }
}
