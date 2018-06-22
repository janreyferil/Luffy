<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Status;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\RegistersRequest;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create_user(RegistersRequest $request)
    {
        $_role = 'user';
        User::create([
            'first' => $request['first'],
            'last' => $request['last'],
            'role' => $_role,
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
        $user = User::orderBy('id','desc')->first();
        Status::create([
            'user_id' => $user->id
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Your request was successfully sent to admin, wait for your response if you will be join here!!'
        ]);
    }

    protected function create_admin(RegistersRequest $request)
    {
        $_role = 'admin';
        User::create([
            'first' => $request['first'],
            'last' => $request['last'],
            'role' => $_role,
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your request was successfully sent to admin, wait for your response if you join here!!'
        ]);
    }
}
