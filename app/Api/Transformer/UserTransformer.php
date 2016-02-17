<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/16
 * Time: 18:21
 */

namespace App\Api\Transformer;


use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user){
        return [
            'id'=>$user['id'],
            'name'=>$user['name'],
            'phone'=>$user['phone'],
            'email'=>$user['email'],
            'poster'=>$user['poster'],
            'qq'=>$user['qq'],
            'wechat'=>$user['wechat'],
            'created_at'=>$user['created_at']->toDateTimeString(),
            'updated_at'=>$user['updated_at']->toDateTimeString(),
        ];
    }
}