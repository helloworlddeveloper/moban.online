<!-- BEGIN: main -->

<div class="block-{BLOCK_ID}">
	<!-- BEGIN: category -->
	<div class="category row">
		<div CLASS="category_title">
			<h4>{CAT.title}</h4>
		</div>
		<div class="cl_logo_slider carousel_slider owl-carousel owl-theme" data-margin="15" data-loop="true" data-nav="true" data-autoplay="true" data-dots="false" data-responsive='{"0":{"items": "2"}, "380":{"items": "2"}, "600":{"items": "4"}, "1000":{"items": "4"}, "1199":{"items": "4"}}'>
		<!-- BEGIN: loop_album -->
			<div class="item">
			<div class="box_shadow1 radius_all_10 animation animated fadeInUp" data-animation="fadeInUp" data-animation-delay="0.02s" style="animation-delay: 0.02s; opacity: 1;; margin-bottom: 20px">
				<div class="radius_ltrt_10">
					<a title="{ALBUM.title}" href="{ALBUM.link}">
						<img src="{ALBUM.img}" alt="{ALBUM.title}" />
					</a>
				</div>
				<div class="blog_footer blog_footer2 bg-white radius_lbrb_10">
					<a title="{ALBUM.title}" href="{ALBUM.link}"><strong>{ALBUM.title}</strong></a>
				</div>
			</div>
		</div>
		<!-- END: loop_album -->
		</div>
	</div>
	<!-- END: category -->
</div>
<style>
	.category_title{
		width: 100%;
	}
</style>
<!-- END: main -->