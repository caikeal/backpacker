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
            'phone'=>'required_without:email|regex:/^1[34578][0-9]{9}$/',
            'email' => 'required_without:phone|email',
            'password' => 'required|min:6|max:50',
        ];
    }

    public function messages()
    {
        return [
            'phone.required_without'=>'手机号必填',
            'phone.regex'=>'手机号格式错误',
            'email.required_without'=>'邮箱必填',
            'email.email'=>'邮箱规则不符合',
            'password.required'=>'密码必填',
            'password.min'=>'密码最少6个字符串',
            'password.max'=>'密码最多50个字符串',
        ];
    }
}