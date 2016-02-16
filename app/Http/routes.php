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
    });
});

//$api->version('v2',function($api){
//    // 更新用户 token
////    $api->get('upToken', 'App\Http\Controllers\Api\V1\AuthenticateController@upToken');
//    // 【用户】
//    // 获取当前用户信息
//    $api->get('me', 'App\Http\Controllers\Api\V1\UserController@me');
//    // 修改当前用户信息
//    $api->post('me', 'App\Http\Controllers\Api\V1\UserController@up');
//});

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
