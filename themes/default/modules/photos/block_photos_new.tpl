<!-- BEGIN: slide -->
<div class="MagicSlideshow album-{BLOCK_ID}" data-options="selectors: bottom; selectors-style: thumbnails; selectors-size: 40px;">
	<!-- BEGIN: loop_album -->
	<img src="{ALBUM.file}" alt="{ALBUM.name}" class="img-responsive"/>
	<!-- END: loop_album -->
</div>

<!-- BEGIN: js -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/plugins/magicslideshow/magicslideshow.css" type="text/css" rel="stylesheet" media="all" />
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/plugins/magicslideshow/magicslideshow.js"></script>
<!-- END: js -->
<!-- END: slide -->

<!-- BEGIN: main -->
<div class="row block-{BLOCK_ID}">
	<!-- BEGIN: loop_album -->
	<div class="col-sm-6 col-md-6">
		<a class="images" href="{ALBUM.link}" title="{ALBUM.name}">
			<img src="{ALBUM.thumb}" alt="{ALBUM.name}" class="img-responsive"/>
		</a>
		<a href="{ALBUM.link}" title="{ALBUM.name}"><span><strong>{ALBUM.name}</strong></span></a>
		<p>{ALBUM.description}</p>
	</div>
	<!-- END: loop_album -->
</div>
<!-- END: main -->
