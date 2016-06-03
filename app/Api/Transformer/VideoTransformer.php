<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/2
 * Time: 14:09
 */

namespace App\Api\Transformer;


use App\Model\Video;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends TransformerAbstract
{
    public function transform(Video $video)
    {
        return [
            'id' => $video['id'],
            'user_id' => $video['user_id'],
            'src' => $video['src'],
            'desc' => $video['desc'],
            'created_at' => $video['created_at']->toDateTimeString(),
            'updated_at' => $video['updated_at']->toDateTimeString(),
            'view_num' => $video['view_num'],
            'comment_num' => $video['comment_num'],
            'publisher' => $this->personTransform($video['publisher'])
        ];
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
}