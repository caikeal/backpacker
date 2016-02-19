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
        $content=$request->getContent();
        Log::info($content);
        $fileAll=[];
        $fileAll=json_decode($content,true);
        $user_id=$fileAll["uid"];
        $src=env('QINIU_IP')."/".$fileAll["fkey"];
        $name=$fileAll["fname"];
        if($fileAll["desc"]){
            $desc=$fileAll["desc"];
        }
        //t=1随拍视频
        if($fileAll['t']==1){
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