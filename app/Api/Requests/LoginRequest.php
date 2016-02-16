<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/16
 * Time: 13:32
 */

namespace App\Api\Requests;


use Dingo\Api\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'phone'=>'required_without_all:email,open_id|regex:/^1[34578][0-9]{9}$/',
            'email' => 'required_without_all:phone,open_id|email',
            'open_id'=>'required_without_all:phone,email',
            'password' => 'required_with:phone,email|min:6|max:50',
            'auth_name'=>'required_with:open_id|in:'.env('THIRD_AUTH')
        ];
    }

    public function messages()
    {
        return [
            'phone.required_without_all'=>'手机号必填',
            'phone.regex'=>'手机号格式错误',
            'email.required_without_all'=>'邮箱必填',
            'email.email'=>'邮箱规则不符合',
            'open_id.required_without_all'=>'第三方登录必填',
            'auth_name.required_with'=>'第三方登录必填',
            'auth_name.in'=>'暂不支持该第三方登录',
            'password.required_with'=>'密码必填',
            'password.min'=>'密码最少6个字符串',
            'password.max'=>'密码最多50个字符串',
        ];
    }
}