<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/2
 * Time: 11:17
 */

namespace App\Api\Controllers\v1;


use App\Api\Controllers\BaseController;
use App\Api\Requests\CommentRequest;
use App\Api\Transformer\CommentTransformer;
use App\Model\Comment;
use App\Model\Video;
use Backpacker\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends BaseController
{
    public $commentService;

    public function __construct()
    {
        $this->commentService = new CommentService();
    }

    public function index(Request $request){
        $video_id = $request->get('vid');//视频id

        if (empty($video_id)){
            return $this->response->error('缺少参数！',422);
        }

        //获取视频评论列表信息
        $commentList = Comment::with([
            'replier',
            'poster',
            'subComments' => function($query){
                $query->take(5);
            },
            'subComments.replier',
            'subComments.poster'
        ])->where('video_id', $video_id)
            ->where('comment_id', 0)
            ->orderBy('created_at','desc')
            ->paginate(15);

        //使分页的搜索保持条件
        $commentList->appends(['vid' => $video_id]);

        foreach ($commentList as $k=>$v){
            $subNum = 0;
            $subNum = Comment::where("comment_id", $v['id'])->count();
            $commentList[$k]['subNum'] = $subNum;
        }
        
        return $this->response->paginator($commentList, new CommentTransformer());
    }

    public function show($id)
    {
        //获取视频评论列表信息
        $commentList = Comment::with(['replier', 'poster'])->where('comment_id', $id)
            ->paginate(15);
//        dd($commentList->toArray());
        return $this->response->paginator($commentList, new CommentTransformer());
    }

    public function store(CommentRequest $request)
    {
        $token = $request->get('token');
        $video_id = $request->input('vid');
        $content = $request->input('content');
        $receive_id = $request->input('receive_id');
        $receiver = $request->input('receiver');
        $comment_id = $request->input('comment_id');

        if ($token){
            $user=JWTAuth::setToken($token)->authenticate();
        }else{
            $user = [];
        }

        if ($user) {
            //昵称评论
            $publish_id = $user['id'];
            $publisher = $user['name'];
        }else{
            //匿名评论
            $publish_id = 0;
            $publisher = '匿名'.str_random(5);
        }

        if( $this->commentService->isMaster($publish_id, $video_id)){
            $is_master = 1;
        }else{
            $is_master = 0;
        }

        DB::beginTransaction();
        try{
            $newCom = new Comment();
            $newCom->video_id = $video_id;
            $newCom->content = $content;
            $newCom->receive_id = $receive_id;
            $newCom->receiver = $receiver;
            $newCom->comment_id = $comment_id;
            $newCom->publish_id = $publish_id;
            $newCom->publisher = $publisher;
            $newCom->is_master = $is_master;
            $newCom->save();

            Video::where('id',$video_id)->increment('comment_num',1);

            DB::commit();
            return $this->response->item($newCom, new CommentTransformer());
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->response->errorInternal("保存失败！");
        }
    }
}