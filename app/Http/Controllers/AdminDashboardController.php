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

    public function all_user(){
        $status = SR::collection(Status::all());
        return response()->json($status,200);
    }
    
    public function single_user(Status $status){
        if($status->user->role == 'admin') {
            return response()->json([
                "redirect" => true 
            ],200);
        } 
        return response()->json(new SR($status),200);
    }

    public function permissions(){
        $status = SR::collection(Status::where('permission','pending')->get());
        if($status->isEmpty()){
            return response()->json([
                'value' => 0
            ],200);
        }
        return response()->json($status,200);
    }

    public function reports(){
        $status = RR::collection(Report::all());
        if($status->isEmpty()){
            return response()->json([
                'value' => 0
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
