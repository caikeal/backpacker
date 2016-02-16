<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/15
 * Time: 17:48
 */

namespace App\Api\Controllers;

use App\Api\Requests\FastAuthRequest;
use App\Api\Requests\LoginRequest;
use App\Api\Requests\RegisterRequest;
use App\Register;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * 普通登录和涉及第三方登录
     */
    public function authenticate(LoginRequest $request)
    {
        // grab credentials from the request
        if($request->input('phone')&&$request->input('password')) {
            $credentials = $request->only( 'password', 'phone');
        }elseif($request->input('open_id') && $request->input('auth_name')){
            $credentials = [
                $request->input('auth_name')=>$request->input('open_id'),
            ];
        }else{
            $credentials = $request->only('email', 'password');
        }
        try {
            // third auth to verify and login and create a token
            if($request->input('open_id') && $request->input('auth_name') && !$request->input('phone')){
                $user=User::where($credentials)->first();
                $token=JWTAuth::fromUser($user);
            }else
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->error('认证失败', 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->response->error('系统出现故障', 500);
        }

        // all good so return the token
        $result['token']=$token;
        $result['created_at']=Carbon::now()->toDateTimeString();
        $user=JWTAuth::setToken($token)->authenticate();
        $result=array_merge($result,$user->toArray());
        return $this->response->array($result);
    }

    /**
     * @param RegisterRequest $request
     * @return mixed
     * 普通注册
     */
    public function register(RegisterRequest $request){
        $newUser=[
            'name'=>$request->input('name'),
            'phone'=>$request->input('phone'),
            'password'=>bcrypt($request->input('password')),
        ];
        $user=User::create($newUser);
        $token=JWTAuth::fromUser($user);

        return $this->response->array(['token'=>$token,'user_id'=>$user['id']]);
    }

    /**
     * @param FastAuthRequest $request
     * 第三方登录快速注册
     */
    public function authRegister(FastAuthRequest $request){
        $is_exist=User::where($request->input('auth_name'),"=",$request->input('open_id'))->count();
        if($is_exist){
            return $this->response->error("该第三方账号已被绑定",409);
        }
        $newUser = [
            'name' => $request->input('name') . "_" . strtoupper(str_random(11)),
            $request->input('auth_name') => $request->input('open_id'),
        ];
        if($request->input('poster')) {
            $newUser=array_merge($newUser,['poster' => $request->input('poster')]);
        }
        $user=User::create($newUser);
        $token=JWTAuth::fromUser($user);

        return $this->response->array(['token'=>$token,'user_id'=>$user['id']]);
    }

    /**
     * @param $openId
     * @param Request $request
     * 验证是否为有效的第三方登录
     */
    public function verifyAuth($openId,Request $request){
        $thirdAuth=explode(",",env(THIRD_AUTH));
        if(!in_array($request->input('name'),$thirdAuth)){
            return $this->response->error("参数错误",401);
        }
        $thirdName=$request->input('name')."_login";
        $exist=User::where($thirdName,"=",$openId)->first();
        $result['is_exist']=$exist?1:0;
        return $this->response->array($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 通过token获取用户信息
     */
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    /**
     * @param $phone
     * 发送验证码
     */
    public function show($phone){
        //验证手机号
        $is_phone=is_numeric($phone)&&preg_match('/^13\d{9}$|^14\d{9}$|^15\d{9}$|^17\d{9}$|^18\d{9}$/',$phone);
        if(!$is_phone){
            return $this->response->error("手机号错误",400);
        }
        $phoneSms=Register::where('phone','=',$phone)->orderBy('created_time','desc')->take(1)->first();
        //短信间隔时间3min
        if($phoneSms && $phoneSms['created_time']+3*60>time()){
            return $this->response->error("验证码已经发送",429);
        }
        /*
         * 发送验证码方法
         */
        $sms="123456";
        /*
         * 将验证码保存
         */
        $register=new Register();
        $register->phone=$phone;
        $register->sms=$sms;
        $register->created_time=time();
        $register->save();

        $result['sms']=$register->sms;
        $result['expire_time']=$register->created_time+30*60;
        return $this->response->array($result);
    }
}