<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/3
 * Time: 13:40
 */

namespace App\Api\Transformer;

use App\Model\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $comment)
    {
        return $this->allComment($comment);
    }

    /**
     * 用户信息
     * @param array $person
     * @return array
     */
    protected function personTransform($person)
    {
        return[
            'id' => $person['id'],
            'name' => $person['name'],
            'poster' => $person['poster']
        ];
    }

    /**
     * 单独评论信息
     * @param array $comment
     * @return array
     */
    protected function commentsTransform($comment)
    {
        return [
            'id' => $comment['id'],
            'video_id' => $comment['video_id'],
            'content' => $comment['content'],
            'publish_id' => $comment['publish_id'],
            'publisher' => $comment['publisher'],
            'receive_id' => $comment['receive_id'],
            'receiver' => $comment['receiver'],
            'comment_id' => $comment['comment_id'],
            'is_master' => $comment['is_master'],
            'created_at' => $comment['created_at']->toDateTimeString(),
            'from_now' => $comment['from_now'],
            'replier' => $this->personTransform($comment['replier']),
            'poster' => $this->personTransform($comment['poster']),
        ];
    }

    /**
     * 所有评论格式
     * @param $comments
     * @return array
     */
    protected function allComment($comments)
    {
        $commentList = [];

        $tpl = $this->commentsTransform($comments);
        $tpl['sub_num'] = $comments['subNum'];
        $tpl['sub_comments'] = [];

        if ($comments['subComments']) {
            foreach ($comments['subComments'] as $kk => $vv) {
                $tpl['sub_comments'][] = $this->commentsTransform($vv);
            }
        }
        $commentList = $tpl;
        unset($tpl);

        return $commentList;
    }
}