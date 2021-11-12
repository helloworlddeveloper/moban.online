<!-- BEGIN: main -->
<div id="header" class="clearfix">
    <a class="logo" title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" height="{LOGO_HEIGHT}" alt="{SITE_NAME}" /></a>
    <div class="title">{DETAIL.title}</div>
    <div class="example" style="line-height: 2rem">
        <div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
        <div class="fb-send" data-href="{SELFURL}"></div>
    </div>
</div>
<!-- video blob -->
<link href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/jsblob/video-js.css" rel="stylesheet">
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/jsblob/video.js"></script>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/jsblob/videojs-contrib-hls.js"></script>
<!-- video blob -->
<!-- video youtube -->
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/jsblob/videoyoutube.js" type="text/javascript"></script>
<script src="https://www.youtube.com/player_api" type="text/javascript"></script>
<!-- video youtube -->
<!-- js baigiang -->
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/jsblob/baigiang.js"></script>
<!-- js baigiang -->
<div id="main" class="clearfix">
   <div class="left-col">
      <div id="video-box">
         <div class="center-block">
            <div class="video">
                <div id="embed_video_youtube" style="display:none"></div>
                <video id="embed_video_wowza" style="display:none" poster="{DETAIL.image}" style="width:100%;" controls autoplay loop preload="auto" class="video-js vjs-default-skin" >
                    <source src="{server_streaming}{VIDEO_INFO.extension}:{VIDEO_INFO.video_path}/playlist.m3u8" type="application/x-mpegURL" />
                </video>
               <div id="embed_video_container">
                    <!-- BEGIN: streamingphp -->
                    <video poster="{DETAIL.image}" width="100%" src="{video_show}" controls loop preload="auto" poster="" >HTML5 Video is required for this example</video>
                    <script type="text/javascript">
                        setInterval(function(){
                        $('#videotime').html($('#embed_video_container').find('video').get(0).currentTime);
                        $('#videoduration').html($('#embed_video_container').find('video').get(0).duration);    
                        },500)
            		</script>
                   <!-- END: streamingphp -->
                   <!-- BEGIN: videoyoutube -->
                    <script type="text/javascript">
                        $('#embed_video_youtube').show();
            			function onYouTubePlayerAPIReady() {
            			  createYTPlayer('embed_video_youtube', '405', '720', '{id_video_youtube}', {});
            			}
            		</script>
                    <!-- END: videoyoutube -->
                   <!-- BEGIN: wowzastreaming -->
                    <script type="text/javascript">
                            $('#embed_video_wowza').show();
                            (function(window, videojs) {
                                var playerblob = videojs('embed_video_wowza');
                            });
                            }(window, window.videojs));
                            setInterval(function(){
                            $('#videotime').html($('#embed_video_wowza').get(0).currentTime);
                            $('#videoduration').html($('#embed_video_wowza').get(0).duration);    
                            },500)
                    </script>
                    <!-- END: wowzastreaming -->
                </div>
            </div>
            <div id="show_side_bar_btn_blur" style="padding-top: 23%; position: absolute; display: none;"><img onclick="manualShowSidebar();" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/showico.png" /></div>
            <div id="show_side_bar_btn_hover" style="padding-top: 23%; position: absolute; display: none;"><img onclick="manualShowSidebar();" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/showico.png" /></div>
            <!-- BEGIN: lesson -->
            <div class="details" id="left_sidebar">
                <button type="button" class="close" onclick="manualHideSidebar();"><span class="icon close-icon"></span></button>
               <ul>
                    <!-- BEGIN: loop -->
                  <li id="lesson_{VIDEO_INFO.video_key}" item-order="{VIDEO_INFO.video_key}" item-id="{DETAIL.id}" class="lesson playlist_menu">
                     <div class="clearfix"></div>
                     <input id="item-title-{VIDEO_INFO.video_key}" value="{VIDEO_INFO.video_title}" type="hidden" />
                     <a onclick="changeVideo({DETAIL.id}, {VIDEO_INFO.video_key});">{VIDEO_INFO.video_title}</a>
                  </li>
                  <!-- END: loop -->
               </ul>
            </div>
            <!-- END: lesson -->
            <div class="top-panel">
               <span style="display:none" id="videoduration">0</span>
               <span style="display:none" id="videotime">0</span>
            </div>
            <!-- BEGIN: fileaddtack -->
            <div class="bottom-panel">
               <span class="icon note-icon"></span>
               <a href="{DETAIL.fileaddtack}" target="_bank" title="{LANG.fileaddtack_download}"><b class="text-white">{LANG.fileaddtack_download}</b></a>
            </div>
            <!-- END: fileaddtack -->
         </div>
      </div>
   </div>
   <div class="right-col">
      <div id="comments-box">
          {CONTENT_COMMENT}
      </div>    
   </div>
</div>
<!-- BEGIN: popuplogin -->
<script type="text/javascript">
    loginForm('');
</script>
<!-- END: popuplogin -->
<!-- END: main -->