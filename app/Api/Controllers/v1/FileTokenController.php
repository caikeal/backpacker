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
    public function show($id){
        $disk=QiniuStorage::disk('qiniu');
        $policy=array(
            'callbackUrl' => url('api/v1/file/info'),
            'callbackBody' => '{"fname":"$(fname)", "fkey":"$(key)", "desc":"$(x:desc)", "uid":' . $id . ',"name":"user_poster"}'
        );
        $token=$disk->uploadToken(time().".jpg",$policy);
        return $this->response->array(['token'=>$token]);
    }
}