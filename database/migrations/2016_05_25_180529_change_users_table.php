<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->increments('id')->comment("自增id")->change();
            $table->string('name')->comment("用户名")->change();
            $table->string('phone')->comment("手机号")->change();
            $table->string('email')->comment("邮箱")->change();
            $table->string('password', 60)->comment("密码")->change();
            $table->rememberToken()->comment("web登录token")->change();
            $table->string('poster')->comment("头像")->change();
            $table->string('qq')->comment("qq")->change();
            $table->string('weixin')->comment("weixin")->change();
            $table->string('sina')->comment("sina")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
