<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="评论详情页">
    <meta name="description" content="具体评论内容">
    <meta name="keywords" content="">
    <meta name="site" content="odeen">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>视频评论详情</title>
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
    <meta name="apple-mobile-web-app-title" content="评论详情"/>
    <link rel="apple-touch-icon-precomposed" href="../dist/icons/72.png">
    <link rel="stylesheet" href="../dist/css/normalize.css">
    <link rel="stylesheet" href="../dist/icons/iconfont.css">

    <!-- 手机格式 -->
    <link rel="stylesheet" type="text/css" href="../dist/css/mobile.css">
</head>
<body id="app" style="margin-bottom: 60px;">
<div id="wrapper">
    <div class="comment">
        <ul class="comment-list">
            <li class="comment-content">
                <div class="cotent-container clearfix">
                    <a href="#" class="comment-poster">
                        <img src="{{ $comment['poster']['poster'] ? $comment['poster']['poster'] : '../dist/imgs/Hello.png' }}" alt="头像">
                    </a>
                    <div class="main">
                        <div class="main-head">
                            <a href="#">{{ $comment['poster']['name'] ? $comment['poster']['name'] : $comment['publisher'] }}</a>
                            <ul class="tag-list">
                                @if($comment['is_master'])
                                <li class="tag">楼主</li>
                                @endif
                            </ul>
                        </div>
                        <div class="main-content" @click="setReplier({{ $comment['publish_id'] }}, '{{ $comment['poster']['name'] ? $comment['poster']['name'] : $comment['publisher'] }}')">{{ $comment['content'] }}</div>
                        <div class="main-control">
                            <span class="main-time">{{ $comment['from_now'] }}</span>
                            <a href="#" class="reply-ctrl" @click.prevent="setReplier({{ $comment['publish_id'] }}, '{{ $comment['poster']['name'] ? $comment['poster']['name'] : $comment['publisher'] }}')">
                                <i class="iconfont icon-huifu reply"></i>
                            </a>
                            <span class="report-ctrl" @click="report(1)">
                                <i class="iconfont icon-report report"></i>
                            </span>
                        </div>
                        <div class="main-reply">
                            <ul>
                                <li class="reply-list" v-cloak v-for="comment in commentList" @click="setReplier(comment.publish_id, comment.poster.name ? comment.poster.name : comment.publisher)">
                                    <a href="#">@{{ comment.poster.name ? comment.poster.name : comment.publisher }}</a>
                                    <ul class="tag-list">
                                        <li class="tag" v-if="comment.is_master">楼主</li>
                                    </ul>
                                    &nbsp;:&nbsp;
                                    <span class="at-replier">
										回复&nbsp;<span>@{{ comment.replier.name ? comment.replier.name : comment.receiver }}</span>&nbsp;:
									</span>
                                    <span class="reply-content">@{{ comment.content }}</span>&nbsp;&nbsp;
                                    <span class="reply-time">@{{ comment.from_now }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<div class="answer" v-show="replyBar" transition="appear"  v-cloak>
    <input type="text" name="answer" placeholder="  回复@{{ receiver }}" v-model="comment" @keyup.enter="reply">
    <div class="btn btn-inline" @click="reply">发送</div>
</div>

<!-- jquery -->
<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>

<!-- iscroll -->
<script src="http://cdn.bootcss.com/iScroll/5.2.0/iscroll.min.js"></script>

<!-- vue -->
<script src="http://cdn.bootcss.com/vue/1.0.24/vue.js"></script>

<script type="text/javascript">
    var myScroll;

    function loaded() {
        myScroll = new IScroll('#wrapper', {mouseWheel: true});
    }
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
            vid: {{ $comment['video_id'] }},
            receive_id: {{ $comment['publish_id'] }},
            receiver: "{{ $comment['poster']['name'] ? $comment['poster']['name'] : $comment['publisher'] }}",
            comment_id: {{ $comment['id'] }},
            comment: "",
            commentList: [],
            pageInfo: [],
            replyBar: true
        },
        created: function(){
            //获取具体评论接口数据
            $.ajax({
                url: '../api/v1/comment/'+{{ $comment['id'] }},
                dataType: 'json',
                headers: {
                    'X-Api-Version': 1
                },
                method: 'GET'
            }).done(function (msg) {
                this.commentList = msg.data;
                this.pageInfo = msg.meta.pagination;
            }.bind(this)).fail(function (error) {
                alert("网络错误！");
            });
        },
        ready: function(){
            var _this = this;
            var pull = 0;
            var beforeScroll = 0;
            $(window).scroll(function(){
                //监听显示回复框
                var scrollTop = $(window).scrollTop();
                if (scrollTop>beforeScroll && !$("[name=answer]").is(":focus")) {
                    if (_this.replyBar != false) {
                        _this.replyBar = false;
                    }
                }else if (scrollTop<beforeScroll) {
                    if (_this.replyBar != true) {
                        _this.replyBar = true;
                    }
                }
                beforeScroll = scrollTop;

                //监听下拉刷新
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
                        'comment_id': _this.comment_id
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
                        'comment_id': _this.comment_id,
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

            setReplier: function (rid, rer) {
                this.receive_id = rid;
                this.receiver = rer;
                this.replyBar = true;
                this.comment = "";
                return true;
            }
        }
    });
</script>
</body>
</html>