<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/22
 * Time: 17:03
 */

namespace App\Api\Controllers\v1;


use App\Api\Controllers\BaseController;
use App\Video;

class VideoController extends  BaseController
{
    public function index(){

    }
    public function show($id){
        $video=Video::findOrFail($id);
        return view('video.video',compact('video'));
    }
}