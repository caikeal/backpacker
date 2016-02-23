<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//api接管路由
$api = app('Dingo\Api\Routing\Router');
$api->version(['v1','v2'],function($api){
    $api->group(['namespace'=>'App\Api\Controllers','middleware'=>['version','api']],function($api){
        //获取验证码
        $api->get('sms/{phone}','AuthController@show');
        //注册
        $api->post('register','AuthController@register');
        //登录
        $api->post('login','AuthController@authenticate');
        //验证第三方授权是否存在
        $api->get('verifyauth/{openId}','AuthController@verifyAuth');
        //第三方授权快速注册
        $api->post('fastauth','AuthController@authRegister');
    });
});
$api->version('v1', function ($api) {
    $api->group(['namespace'=>'App\Api\Controllers\v1','prefix'=>'v1','middleware'=>['version','api','jwt.auth']],function($api){
        $api->resource('user','UserController');
        //七牛上传token请求
        $api->get('file/token/{id}','FileTokenController@show');
    });

    $api->group(['namespace'=>'App\Api\Controllers\v1','prefix'=>'v1'],function($api){
        //回调请求地址
        $api->post('file/info','FileInfoController@store');

        $api->resource('video','VideoController');
    });
});

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
