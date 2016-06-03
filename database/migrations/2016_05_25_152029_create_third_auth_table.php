<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThirdAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_auth', function (Blueprint $table) {
            $table->increments('id')->comment("自增id");
            $table->integer('user_id',false,true)->index()->comment("user表的id");
            $table->string('oauth_name')->comment("第三方登录名");
            $table->string('oauth_id')->comment("oauth认证id");
            $table->string('oauth_access_token')->comment("oauth认证token");
            $table->string('oauth_expires')->comment("oauth认证失效时间");
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
        Schema::drop('third_auth');
    }
}
