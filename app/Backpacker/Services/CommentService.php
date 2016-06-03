<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/3
 * Time: 17:13
 */

namespace Backpacker\Services;


use App\Model\Video;

class CommentService
{
    /**
     * 判断是否是视频发布者
     * @param $publish_id
     * @param $video_id
     * @return bool
     */
    public function isMaster($publish_id, $video_id)
    {
        $video = Video::select(['user_id'])->find($video_id);
        if (!$video){
            return false;
        }
        if ($publish_id ==  $video['user_id']){
            return true;
        }
        return false;
    }
}