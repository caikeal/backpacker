<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/3
 * Time: 16:52
 */

namespace App\Api\Requests;


use App\Api\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'vid' => 'required',
            'content'=>'required',
            'receive_id' => 'required',
            'receiver' => 'required',
            'comment_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'vid.required'=>'视频必填',
            'content.required'=>'评论内容必填',
            'receive_id.required'=>'评论人必填',
            'receiver.required'=>'评论人必填',
            'comment_id.required'=>'评论条目必填'
        ];
    }
}