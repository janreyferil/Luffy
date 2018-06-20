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
use App\Http\Resources\Primary\UsersResource as UR;
use App\Http\Resources\Primary\ReportsResource as RR;
use App\Http\Resources\Primary\PostsResource as PR;
use App\Http\Resources\Primary\PostCommentsResource as PCR;
use App\Http\Resources\Primary\PostReactsResource as PRR;
use App\Http\Resources\Primary\CommentReactsResource as CRR;
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
                "user" => new UR($user)
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
            "user" => new UR($user)
        ],200);
    }

    public function report(ReportsRequest $request){
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
            "report" => new RR($report)
        ],200);
    }

    public function react_history(){
        $user = User::find(auth()->user()->id)->first();
        return response()->json([
            "post_reacts" => PRR::collection($user->user_post_reacts),
            "comment_reacts" => CRR::collection($user->user_comment_reacts),
        ],200);
    }

    public function my_activity(){
        $posts = Post::where('user_id',auth()->user()->id)->get();
        $comments = PostComment::where('user_id',auth()->user()->id)->get();
        return response()->json([
            "post" => PR::collection($posts),
            "comment" => PCR::collection($comments) 
        ],200);
    }
}
