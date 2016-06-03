<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("video_id", false, true)->index()->comment("对应video的id");
            $table->string("content")->comment("评论内容");
            $table->integer("publish_id", false, true)->index()->comment("发表评论用户id");
            $table->string("publisher")->comment("评论人名称");
            $table->integer("receive_id", false, true)->index()->comment("接受评论的用户id");
            $table->string("receiver")->comment("接受评论人名称");
            $table->integer("comment_id", false, true)->index()->comment("被回复的评论id")->default(0);
            $table->tinyInteger("is_master", false, true)->comment("是否是楼主，默认0，1是，0不是")->default(0);
            $table->softDeletes()->comment("评论的软删除");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments');
    }
}
