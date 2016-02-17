<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/17
 * Time: 15:18
 */

namespace App\Api\Controllers\v1;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Api\Controllers\BaseController;

class FileInfoController extends BaseController
{
    public function store(Request $request){
        if($request->input('name')=='user_poster'){
            User::where("id","=",$request->input("uid"))->update(['poster'=>$request->input("fname")]);
        }
        $resp = array('ret' => 'success');
        return $resp;
    }
}