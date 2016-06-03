<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="摄界视频列表">
    <meta name="description" content="摄界视频列表">
    <meta name="keywords" content="视频列表">
    <meta name="site" content="odeen">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>视频列表</title>
    <!-- ignore some default set on mobile -->
    <meta name="format-detection" content="telephone=no">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="dist/icons/72.png">
    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="摄界视频列表"/>
    <link rel="apple-touch-icon-precomposed" href="dist/icons/72.png">
    <link rel="stylesheet" href="dist/css/normalize.css">
    <link rel="stylesheet" href="dist/icons/iconfont.css">

    <!-- 手机格式 -->
    <link rel="stylesheet" type="text/css" href="dist/css/mobile.css">
</head>
<style type="text/css">
    body{
        background-color: #fff;
    }
    #wrapper{
        padding: 10px 10px;
    }
    .iconfont.icon-play, .iconfont.icon-huifu {
        color: #B3B3B3;
    }
    .iconfont.icon-play {
        vertical-align: bottom;
    }
    .iconfont.icon-huifu {
        font-weight: 600;
    }
</style>
<body id="app">
<div id="wrapper">
    <div class="video-list">
        <div class="video-row">
            <div class="video-item" v-for="video in videoList">
                <a href="video/@{{ video.id }}" class="video-content">
                    <div class="content-thumb">
                        <div class="content-thumb-default">
                            <img src="dist/imgs/default-video.png" alt="默认logo图片">
                        </div>
                        <div class="content-thumb-real" :style="{backgroundImage: 'url(' + video.src + '?vframe/jpg/offset/1/imageView2/1/w/448/h/252)'}">
                        </div>
                        <b class="play-icon"></b>
                    </div>
                    <div class="content-meta">
                        <div class="meta-name" v-cloak>来自@{{ video.publisher.name }}</div>
                        <div class="meta-desc">
                            <i title="播放" class="iconfont icon-play"></i>
                            <span class="meta-play" v-cloak>@{{ video.view_num }}</span>
                            <i title="评论" class="iconfont icon-huifu"></i>
                            <span class="meta-comment" v-cloak>@{{ video.comment_num }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<!-- jquery -->
<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>

<!-- vue -->
<script src="http://cdn.bootcss.com/vue/1.0.24/vue.js"></script>

<script type="text/javascript">
    new Vue({
        el: '#app',
        data: {
            videoList: [],
            pageInfo: []
        },
        created: function(){
            //获取接口数据
            $.ajax({
                url: 'api/v1/video',
                dataType: 'json',
                headers: {
                    'X-Api-Version': 1
                },
                method: 'GET'
            }).done(function (msg) {
                this.videoList = msg.data;
                this.pageInfo = msg.meta.pagination;
            }.bind(this)).fail(function (error) {
                alert("网络错误！");
            });
        },
        ready: function(){
            var pull = 0;
            var _this = this;
            var scrollEvent = $(window).scroll(function () {
                var scrollTop = $(window).scrollTop();
                var wrapperHeight = $(document).height();
                var windowHeight = $(window).height();
                var url = _this.pageInfo.links.next;

                if(windowHeight + scrollTop + 300 >= wrapperHeight && pull === 0 && url){
                    //锁定请求
                    pull = 1;
                    //获取接口数据
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        headers: {
                            'X-Api-Version': 1
                        },
                        method: 'GET'
                    }).done(function (msg) {
                        if (msg.data.length){
                            for(var i = 0; i < msg.data.length; i++){
                                _this.videoList.push(msg.data[i]);
                            }
                            _this.pageInfo = msg.meta.pagination;
                        }else{
                            alert("已无更多内容！");
                        }
                        pull = 0;
                        return false;
                    }).fail(function (error) {
                        alert("网络错误！");
                        pull = 0;
                        return false;
                    });
                }
            });
        }
    });
</script>
</body>
</html>