<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'src', 'desc', 'name',
    ];

    //发布者
    public function publisher()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    //评论
    public function comments()
    {
        return $this->hasMany('App\Model\Comment', 'video_id');
    }
}
