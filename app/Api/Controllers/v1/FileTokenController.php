<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/17
 * Time: 15:15
 */

namespace App\Api\Controllers\v1;

use Illuminate\Http\Request;
use App\Api\Controllers\BaseController;
use zgldh\QiniuStorage\QiniuStorage;

class FileTokenController extends BaseController
{
    public function show($id,Request $request){
        $disk=QiniuStorage::disk('qiniu');

        //随拍视频上传t=1
        if($request->input('t')==1) {
            $policy = array(
                'callbackUrl' => url('api/v1/file/info'),
                'callbackBody' => '{"fname":"$(fname)", "fkey":"$(key)", "desc":"$(x:desc)", "uid":' . $id . ',"t":"1"}'
            );
            $key = "random_shoot/" . str_random(2) . time() . $id;
        }
        $token=$disk->uploadToken($key,$policy);
        return $this->response->array(['upToken'=>$token,'k'=>$key]);
    }
}