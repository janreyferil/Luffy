<?php

use Illuminate\Http\Request;
use App\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Test


//User
Route::middleware('user')->post('register/user','Auth\RegisterController@create_user');
Route::middleware('admin')->post('register/admin','Auth\RegisterController@create_admin');

// Admin
Route::get('status/pending','AdminDashboardController@all_user_pending');
Route::get('status/failed','AdminDashboardController@all_user_failed');
Route::get('status/success','AdminDashboardController@all_user_success');
Route::put('status/{status}','AdminDashboardController@update');
//Route::get('permissions','AdminDashboardController@permissions');
Route::get('reports','AdminDashboardController@reports');

//User
Route::get('home','UserDashboardController@home');
Route::get('user/posts','UserDashboardController@my_activity');

//Post
Route::apiResource('posts','PostsController');

//Comment
Route::group(['prefix'=>'posts'],function(){
    Route::apiResource('{post}/comments','PostCommentsController');
});

//Report
Route::post('reports','UserDashboardController@report');

//ReactHistory
Route::get('history/reacts','UserDashboardController@react_history');

//React
Route::post('react/posts/{post}','ReactsController@post_react');
Route::post('react/posts/{post}/comments/{comment}','ReactsController@comment_react');

//AccessControl
Route::get('post/access_control/{post}','UserDashboardController@accessControl');
Route::get('check','AdminDashboardController@adminControl');

//Account Settings
Route::put('setting/account','UserDashboardController@changeAccount');
Route::put('setting/info','UserDashboardController@changeInfo');