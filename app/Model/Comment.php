<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $table = 'comments';

    /**
     * 追加发布时间距现在的时间
     * @var array
     */
    protected $appends = ['from_now'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id', 'publish_id', 'publisher', 'receive_id', 'receiver',
        'comment_id', 'is_master', 'content'
    ];

    protected $dates = ['deleted_at'];

    /**
     * 评论人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poster()
    {
        return $this->belongsTo('App\Model\User', 'publish_id', 'id');
    }

    /**
     * 回复评论的人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replier()
    {
        return $this->belongsTo('App\Model\User', 'receive_id');
    }

    /**
     * 子评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subComments()
    {
        return $this->hasMany('App\Model\Comment', 'comment_id');
    }

    /**
     * 追加发布时间距现在的时间
     * **距离现在时间**      **显示格式**
     * < 1小时                 xx分钟前
     * 1小时-24小时            xx小时前
     * 1天-10天                xx天前
     * >10天                   直接显示日期
     * @return string|static
     */
    public function getFromNowAttribute()
    {
        Carbon::setLocale('zh');
        if (Carbon::now() >= Carbon::parse($this->attributes['created_at'])->addDays(10)){
            return Carbon::parse($this->attributes['created_at'])->toDateTimeString();
        }

        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
}
