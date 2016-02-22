<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Shoot Photo</title>
        <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
        <link href="http://vjs.zencdn.net/5.0/video-js.min.css" rel="stylesheet">
        <script src="http://vjs.zencdn.net/5.0/video.min.js"></script>
        <style>
            body{
                margin: 0;
                padding: 0;
            }
            .video-js .vjs-big-play-button{
                left: 50%;
                margin-left: -2.1em;
                top: 50%;
                margin-top: -1.4em;
            }
        </style>
    </head>
    <body>
    <video id="cool-video" class="video-js vjs-default-skin" controls
           preload="auto" width="640" height="264" poster="really-cool-video-poster.jpg"
           data-setup='{ "playbackRates":[0.25,0.5,1,1.25,1.5,2]}'>
        <source src="{{$video->src}}" type="video/mp4">
        {{--<source src="really-cool-video.webm" type="video/webm">--}}
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser
            that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
    </video>
    <script>
        var width=window.outerWidth;
        var height=window.outerheight;
        $("#cool-video").attr('width',width);
        $("#cool-video").attr('height',height);
    </script>
    </body>
</html>