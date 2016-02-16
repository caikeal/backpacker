<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/16
 * Time: 11:40
 */

namespace App\Api\Requests;


use Dingo\Api\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|unique:users,name|max:255',
            'phone'=>'required_without:email|regex:/^1[34578][0-9]{9}$/|unique:users,phone',
            'email' => 'required_without:phone|email|unique:users,email',
            'password' => 'required|min:6|max:50',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'昵称必填',
            'name.unique'=>'昵称有重复',
            'name.max'=>'昵称最多255个字符',
            'phone.required_without'=>'手机号必填',
            'phone.unique'=>'手机号有重复',
            'phone.regex'=>'手机号格式错误',
            'email.required_without'=>'邮箱必填',
            'email.email'=>'邮箱规则不符合',
            'email.unique'=>'邮箱有重复',
            'password.required'=>'密码必填',
            'password.min'=>'密码最少6个字符串',
            'password.max'=>'密码最多50个字符串',
        ];
    }
}