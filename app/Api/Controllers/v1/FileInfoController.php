<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/17
 * Time: 15:18
 */

namespace App\Api\Controllers\v1;

use App\User;
use App\Video;
use Illuminate\Http\Request;
use App\Api\Controllers\BaseController;
use Log;

class FileInfoController extends BaseController
{
    public function store(Request $request){
//        Log::info(json_encode($request->all()));
        $user_id=$request->input("uid");
        $src=env('QINIU_IP')."/".$request->input("fkey");
        $name=$request->input("fname");
        if($request->input("desc")){
            $desc=$request->input("desc");
        }
        //t=1随拍视频
        if($request->input('t')==1){
            $video=new Video();
            $video->user_id=$user_id;
            $video->src=$src;
            $video->desc=$desc?$desc:"";
            $video->name=$name;
            $video->save();
            $resp = array('ret' => 'success');
        }else{
            $resp = array('err'=>'DB_Error');
            http_response_code(500);
        }
        return $resp;
    }
}