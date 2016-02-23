<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/16
 * Time: 23:54
 */

namespace App\Api\Requests;

use App\Api\FormRequest;

class FastAuthRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required',
            'poster'=>'url',
            'auth_name' => 'required|in:'.env('THIRD_AUTH'),
            'open_id' => 'required_with:auth_name',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'昵称必填',
            'poster.url'=>'照片无效',
            'auth_name.in'=>'非支持的第三方认证',
            'auth_name.required'=>'第三方认证必填',
            'open_id.exists'=>'非支持的第三方认证',
            'open_id.required_with'=>'第三方认证必填',
        ];
    }
}