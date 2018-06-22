<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use App\Report;
use App\Http\Resources\Primary\StatusesResource as SR;
use App\Http\Resources\Primary\ReportsResource as RR;

class AdminDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware('isadmin');
    }

    public function all_user_pending(){
       
        $status = SR::collection(Status::where('permission','pending')->orderBy('created_at','ASC')->get());
        return response()->json($status,200);
    }

    public function all_user_success(Status $status){
        $status = SR::collection(Status::where('permission','stable')->orWhere('permission','success')->orderBy('updated_at','ASC')->get());
        return response()->json($status,200);
    }
    
    public function all_user_failed(Status $status){
        $status = SR::collection(Status::where('permission','failed')->orderBy('created_at','ASC')->get());
        return response()->json($status,200);
    }

    public function reports(){
        $status = RR::collection(Report::orderBy('created_at','DESC')->get());
        if($status->isEmpty()){
            return response()->json([
                'empty' => true
            ],200);
        }
        return response()->json($status,200);
    }

    public function update(Request $request,Status $status) {
        if($request->option == 'permission'){
            if($status->permission === 'pending') {
                if($request->result == 'success'){
                    $status->permission = 'success';
                } elseif($request->result == 'failed') {
                    $status->permission = 'failed';
                }
            } 
            if($status->permission === 'failed') {
                if($request->result == 'chance') {
                    $status->permission = 'success';
                }
            }
        }
        if($request->option == 'report'){
            if($status->report === 'good') {
                $status->report = 'bad';
                $message = 'I recieved a lot of report to you so I decided to block you in this website';
            } elseif($status->report === 'bad') {
                $status->report = 'back';
                $message = 'I give you a chance because you proof that your not doing bad';
            }
        }
        $status->save();
        return response()->json([
            'success' => true,
            'status' => new SR($status)
        ]);
    }
}
