<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="来自{{ $video['publisher']['name'] }}的视频">
    <meta name="description" content="具体视频内容描述">
    <meta name="keywords" content="">
    <meta name="site" content="odeen">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>来自{{ $video['publisher']['name'] }}的视频详情</title>
    <!-- ignore some default set on mobile -->
    <meta name="format-detection" content="telephone=no">

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="../dist/icons/72.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="来自{{ $video['publisher']['name'] }}的视频"/>
    <link rel="apple-touch-icon-precomposed" href="../dist/icons/72.png">

    <link rel="stylesheet" href="../dist/css/normalize.css">
    <link rel="stylesheet" href="../dist/icons/iconfont.css">

    <!-- 视频 -->
    <link href="http://cdn.bootcss.com/video.js/4.12.10/video-js.min.css" rel="stylesheet">

    <!-- 分享 -->
    <link rel="stylesheet" href="../dist/css/share.min.css">

    <!-- 手机格式 -->
    <link rel="stylesheet" type="text/css" href="../dist/css/mobile.css">
</head>
<body id="app" style="margin-bottom: 60px;">
<img src='{{ $video['src'] ?  $video['src']."?vframe/jpg/offset/1" : ""}}' alt="" id="share-pic">
<div class="loading hide">
    <div class="loading-cover">
        <div class="loading-content">正在加载中。。。</div>
    </div>
</div>
<div id="wrapper">
    <div id="instructions">
        <video id="my_video_1" class="video-js vjs-default-skin" preload="auto" controls
               poster='{{ $video['src'] ?  $video['src']."?vframe/jpg/offset/1" : ""}}'
               data-setup='{ "playbackRates": [1, 1.5, 2]}'>
            <source src="{{ $video['src'] }}" type='video/mp4'/>
            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a web browser that
                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
            </p>
        </video>
    </div>

    <div class="video-opt">
        <span class="ctrl-btn ctrl-share" @click="toggleShow">
        <i class="iconfont icon-share share"></i>
        <span>分享</span>
        </span>
        <span class="ctrl-line"></span>
        <span class="ctrl-btn">
            <i class="iconfont icon-play play-count"></i>
            <span>{{ $video['view_num'] }}</span>
        </span>
    </div>

    <div class="gap"></div>

    <div class="comment">
        <div class="comment-title">评论</div>
        <ul class="comment-list">
            <li class="comment-content" v-for="comment in commentList | orderBy 'created_at' -1" v-cloak>
                <div class="cotent-container clearfix">
                    <a href="#" class="comment-poster">
                        <img :src=" comment.poster.poster " alt="头像">
                    </a>
                    <div class="main">
                        <div class="main-head">
                            <a href="#">@{{ comment.poster.name ? comment.poster.name: comment.publisher }}</a>
                            <ul class="tag-list">
                                <li class="tag" v-if="comment.is_master">楼主</li>
                            </ul>
                        </div>
                        <div class="main-content" @click="changeToDetail(comment.id)">@{{ comment.content }}</div>
                        <div class="main-control">
                            <span class="main-time">@{{ comment.from_now }}</span>
                            <a href="#" @click.prevent="changeToDetail(comment.id)" class="reply-ctrl">
                                <i class="iconfont icon-huifu reply"></i>
                            </a>
                            <span class="report-ctrl" @click="report(1)">
                            <i class="iconfont icon-report report"></i>
                            </span>
                        </div>
                        <div class="main-reply" v-if="comment.sub_comments.length?1:0" @click="changeToDetail(comment.id)">
                            <ul>
                                <li class="reply-list" v-for="subComment in comment.sub_comments" v-cloak>
                                    <a href="#">@{{ subComment.poster.name ? subComment.poster.name : subComment.publisher }}</a>&nbsp;:&nbsp;
                                    <ul class="tag-list">
                                        <li class="tag" v-if="subComment.is_master">楼主</li>
                                    </ul>
                                    <span class="at-replier">
                                        回复&nbsp;<span>@{{ subComment.replier.name ? subComment.replier.name : subComment.receiver}}</span>&nbsp;:
                                    </span>
                                    <span class="reply-content">@{{ subComment.content }}</span>&nbsp;&nbsp;
                                    <span class="reply-time">@{{ subComment.from_now }}</span>
                                </li>
                            </ul>
                            <div class="reply-more" v-if="comment.sub_num>5">
                                <a href="../comment/@{{ comment.id }}">
                                    更多<span class="reply-more-num">@{{ comment.sub_num - 5 }}</span>条回复...
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="answer">
    <input type="text" name="answer" placeholder="  请输入评论..." v-model="comment" @keyup.enter="reply">
    <div class="btn btn-inline" @click="reply">发送
</div>
</div>

<div class="black-wrp" style="display: none;" v-show="show" transition="fade" @click="toggleShow">
<div class="share-box" @click="stopShow">
<div class="share-box-list">
    <div class="social-share" data-sites="weibo,qq,qzone,tencent" style="text-align:center;"></div>
    <div class="share-box-title clearfix"></div>
    <div class="share-box-content clearfix"></div>
    <a class="share-box-cancel clearfix" @click="toggleShow">取消</a>
</div>
</div>
</div>
<!-- jquery -->
<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>

<!-- vue -->
<script src="http://cdn.bootcss.com/vue/1.0.24/vue.js"></script>

<!-- 视频 -->
<script src="http://cdn.bootcss.com/video.js/4.12.10/video.js"></script>

<!-- 分享 -->
<script src="../dist/js/jquery.share.min.js"></script>

<script type="text/javascript">
    videojs("my_video_1", {
        "controls": true,
        "autoplay": false,
        "preload": "auto"
    }).ready(function () {
        var myPlayer = this;    // Store the video object
        var aspectRatio = 9 / 16;

        function resizeVideoJS() {
            // Get the parent element's actual width
            var width = document.getElementById(myPlayer.id()).parentElement.offsetWidth;
            // Set width to fill parent element, Set height
            console.log(width);
            myPlayer.width(width).height(width * aspectRatio);
        }

        resizeVideoJS(); // Initialize the function
        window.onresize = resizeVideoJS; // Call the function on resize
    });
</script>

<script type="text/javascript">
    /**
     * empty
     *
     * @param {Array|Object|String} target
     * @return {Boolean}
     */
    function empty(target) {
        if (target === null || target === undefined || target === "") {
            return true;
        }

        if (Array.isArray(target)) {
            if (target.length > 0) {
                return false;
            }
            if (target.length === 0) {
                return true;
            }
        } else if (typeof target === "object") {
            var count = 0;
            for (var key in target) {
                count++;
                if (count > 0) {
                    return false;
                }
            }
        }

        if (typeof target === "string") {
            if (target == "" || target == " ") {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    new Vue({
        el: '#app',
        data: {
            show: false,
            vid: 0,
            receive_id: 0,
            receiver: "",
            comment: "",
            commentList: [],
            pageInfo: []
        },
        created: function(){
            this.vid = {{ $video['id'] }};
            this.receive_id = {{ $video['publisher']['id'] }};
            this.receiver = "{{ $video['publisher']['name'] }}";
            //获取评论列表接口数据
            $.ajax({
                url: '../api/v1/comment',
                dataType: 'json',
                headers: {
                    'X-Api-Version': 1
                },
                data: {'vid': {{ $video['id'] }}},
                method: 'GET'
            }).done(function (msg) {
                this.commentList = msg.data;
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
                                _this.commentList.push(msg.data[i]);
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
        },
        methods: {
            toggleShow: function (e) {
                if (this.show) {
                    this.show = false;
                } else {
                    this.show = true;
                }
                return false;
            },

            stopShow: function (e) {
                e.stopPropagation();
                return false;
            },

            report: function (msg) {
                var isConfirm = confirm("您确定要举报该用户吗？");
                if (isConfirm) {
                    //处理举报流程
                    alert("举报成功！");
                }
                return false;
            },

            reply: function () {
                //处理发布内容
                if (empty(this.comment) || $.trim(this.comment) == "") {
                    this.comment = "";
                    alert("您还未输入任何内容！");
                    return false;
                }
                var _this = this;
                var searchParams = window.location.search;
                var url = '../api/v1/comment';
                if (searchParams){
                    url = url +  searchParams
                }
                $.ajax({
                    url: url,
                    dataType: 'json',
                    headers: {
                        'X-Api-Version': 1
                    },
                    data: {
                        'vid': _this.vid,
                        'content': _this.comment,
                        'receive_id': _this.receive_id,
                        'receiver': _this.receiver,
                        'comment_id': 0
                    },
                    method: 'POST'
                }).done(function(msg){

                    commentObj = {
                        'id': msg.data.id,
                        'video_id': _this.vid,
                        'content': _this.comment,
                        'publish_id': msg.data.publish_id,
                        'publisher': msg.data.publisher,
                        'receive_id': _this.receive_id,
                        'receiver': _this.receiver,
                        'comment_id': 0,
                        'is_master': msg.data.is_master,
                        'created_at': msg.data.created_at,
                        'from_now': msg.data.from_now,
                        'replier': msg.data.replier,
                        'poster': msg.data.poster,
                        'sub_num': 0,
                        'sub_comments': []
                    };
                    _this.commentList.push(commentObj);
                    _this.comment="";
                    alert("发布成功！");
                }).fail(function(error){
                    alert("网络错误！");
                });

                return false;
            },

            changeToDetail: function (id) {
                var searchParams = window.location.search;
                var url = "../comment/"+id;
                if (searchParams){
                    url = url +  searchParams
                }
                window.location.href = url;
            }
        }
    });
</script>
</body>
</html>