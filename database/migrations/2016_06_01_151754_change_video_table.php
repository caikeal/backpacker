<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->integer('user_id',false,true)->comment("用户id")->change();
            $table->string('src')->comment("视频地址")->change();
            $table->string('desc')->comment("视频描述")->change();
            $table->string('name')->comment("视频名称")->change();
            $table->integer('view_num')->comment("播放次数")->default(0)->change();
            $table->integer('comment_num')->comment("评论次数")->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video', function (Blueprint $table) {
            //
        });
    }
}
