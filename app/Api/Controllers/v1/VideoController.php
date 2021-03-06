<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/22
 * Time: 17:03
 */

namespace App\Api\Controllers\v1;


use App\Api\Controllers\BaseController;
use App\Api\Transformer\VideoTransformer;
use App\Model\Video;

/**
 * Video resource representation.
 *
 * @package App\Api\Controllers\v1
 * @Resource("video", uri="/video")
 */
class VideoController extends  BaseController
{
    /**
     * show video list
     *
     * Get a JSON representation of all the videos.
     *
     * @Get("/{?page}")
     * @Version({"v1"})
     * @Request(headers={"X-Api-Version":"v1"})
     * @Parameters({
     *     @Parameter("page", description="The page of results to view.", default=1),
     * })
     * @Response(200, body={"data":[{"id":1, "name":"foo"},{"id":1, "name":"bar"}]})
     */
    public function index(){
        //获取视频信息（视频发布者，观看人数，评论人数，视频首页图片）
        $videoList = Video::with(['publisher'])->paginate(15);

        return $this->response->paginator($videoList, new VideoTransformer());
    }

    /**
     * show a specific video
     *
     * Get a JSON representation of a video.
     *
     * @Get("/{/id}")
     * @Version({"v1"})
     */
    public function show($id){
        $video=Video::with(['publisher'])->find($id);
        return $this->response->item($video, new VideoTransformer());
    }
}