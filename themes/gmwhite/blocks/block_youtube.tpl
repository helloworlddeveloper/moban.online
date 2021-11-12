<!-- BEGIN: main -->
<style>
.video-list-thumbs{}
.video-list-thumbs > li{
    margin-bottom:12px
}
.video-list-thumbs > li:last-child{}
.video-list-thumbs > li > a{
	display:block;
	position:relative;
	background-color: #b1b1b1;
	color: #fff;
	padding: 8px;
	border-radius:3px
}
.video-list-thumbs > li > a:hover{
	background-color:#000;
	transition:all 500ms ease;
	box-shadow:0 2px 4px rgba(0,0,0,.3);
	text-decoration:none
}
.video-list-thumbs h2{
	bottom: 0;
	font-size: 12px;
	height: 25px;
	margin: 8px 0 0;
    overflow: hidden;
}
.video-list-thumbs .fa-play-circle{
    font-size: 60px;
    opacity: 0.6;
    position: absolute;
    right: 39%;
    top: 31%;
    text-shadow: 0 1px 3px rgba(0,0,0,.5);
}
.video-list-thumbs > li > a:hover .fa-play-circle{
	color:#fff;
	opacity:1;
	text-shadow:0 1px 3px rgba(0,0,0,.8);
	transition:all 500ms ease;
}
.video-list-thumbs .duration{
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 2px;
	color: #fff;
	font-size: 11px;
	font-weight: bold;
	left: 12px;
	line-height: 13px;
	padding: 2px 3px 1px;
	position: absolute;
	top: 12px;
}
.video-list-thumbs > li > a:hover .duration{
	background-color:#000;
	transition:all 500ms ease;
}
@media (min-width:320px) and (max-width: 480px) { 
	.video-list-thumbs .fa-play-circle{
    font-size: 35px;
    right: 36%;
    top: 27%;
	}
	.video-list-thumbs h2{
		bottom: 0;
		font-size: 12px;
		height: 22px;
		margin: 8px 0 0;
	}
}
</style>
<ul class="list-unstyled video-list-thumbs">
    <!-- BEGIN: loopvideo -->
    <li id="{ITEM.id.videoId}" data-title="{ITEM.snippet.title}" class="col-lg-8 col-sm-24 col-xs-24 youtube-video">
		<a href="#{ITEM.id.videoId}" title="{ITEM.snippet.title}">
			<img src="{ITEM.snippet.thumbnails.high.url}" alt="{ITEM.snippet.title}" class="img-responsive" height="130px" />
			<h2>{ITEM.snippet.title}</h2>
			<span class="fa fa-play-circle"></span>
		</a>
	</li>
    <!-- END: loopvideo -->
    <!-- BEGIN: loop_playlist_video -->
    <li id="{ITEM.id.playlistId}" data-title="{ITEM.snippet.title}" class="col-lg-8 col-sm-24 col-xs-24 youtube-video">
		<a href="#{ITEM.id.playlistId}" title="{ITEM.snippet.title}">
			<img src="{ITEM.snippet.thumbnails.high.url}" alt="{ITEM.snippet.title}" class="img-responsive" height="130px" />
			<h2>{ITEM.snippet.title}</h2>
			<span class="fa fa-play-circle"></span>
		</a>
	</li>
    <!-- END: loop_playlist_video -->
</ul>
<div class="text-center"><a href="https://www.youtube.com/channel/{channelId}" target="_blank" class="btn btn-danger btn-lg"><i class="fa fa-youtube"></i> Xem thêm...</a></div>
<div class="clear"></div>
<script>
//For video
$(".youtube-video").click(function(e){
	$(this).children('a').html('<div class="vid"><iframe width="100%" height="" src="https://www.youtube.com/embed/'+ $(this).attr('id') +'?autoplay=1" frameborder="0" allowfullscreen></iframe><h2>'+ $(this).attr('data-title') +'</h2></div>');
    return false;
	 e.preventDefault();
	});
	//For playlist
	$(".youtube-playlist").click(function(e){
	$(this).children('a').html('<div class="vid"><iframe width="100%" height="" src="https://www.youtube.com/embed/videoseries?list='+ $(this).attr('id') +'&autoplay=1" frameborder="0" allowfullscreen></iframe><h2>'+ $(this).attr('data-title') +'</h2></div>');
    return false;
	 e.preventDefault();
	});
	
</script>
<!-- END: main -->