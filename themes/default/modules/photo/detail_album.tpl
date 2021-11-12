<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/plugins/fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/plugins/fancybox/jquery.fancybox.css" media="screen" />
    
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/plugins/fancybox/jquery.fancybox-buttons.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/plugins/fancybox/jquery.fancybox-buttons.js"></script>
<div itemscope itemtype="http://schema.org/ImageObject">
    <div class="alert alert-info clearfix">
        <h1><a itemprop="url" href="{SELFURL}" > <span itemprop="name">{ALBUM.name}</span></a></h1>
        <p><div class="photo-description" itemprop="description"> {ALBUM.description} </div></p>
        <p><span class="contentLocation" itemprop="contentLocation">{ALBUM.capturelocal}</span></p>
    </div>
    <div id="photo-content" class="rows">
            <!-- BEGIN: loop_slide -->
    		<div class="col-md-3 col-sm-4 col-xs-6"><a class="fancybox-buttons uiMediaThumb" data-fancybox-group="button" title="{PHOTO.description}" href="{PHOTO.file}"><div class="thumb" style="background-image: url('{PHOTO.thumb}')"></div></a></div>
            <!-- END: loop_slide -->
    	<div class="clear" style="height: 20px"></div>
      	<div class="fb-comments" data-href="{SELFURL}" data-width="100%" data-numposts="20" data-colorscheme="light"></div>
    </div>
</div>
<script type="text/javascript">
$('.fancybox-buttons').fancybox({
	openEffect  : 'none',
	closeEffect : 'none',
	prevEffect : 'none',
	nextEffect : 'none',
	closeBtn  : false,
	helpers : {
		title : {
			type : 'inside'
		},
		buttons	: {}
	},

	afterLoad : function() {
		this.title = 'Ảnh ' + (this.index + 1) + ' trong số ' + this.group.length + (this.title ? ' - ' + this.title : '');
	}
});
</script>
<div id="photo-album">
    <h2>{LANG.album_in_category}</h2>
	<div class="box-item multi-columns-row" itemscope itemtype="http://schema.org/ImageObject">
		<!-- BEGIN: loop_album -->
		<div class="col-xs-12 col-sm-6 col-md-4 photo-album">
			<div class="photo-hover">
				<div class="photo-image lazyload">
				  <a itemprop="url" href="{OTHER.link}"><img itemprop="image" class="lazy" src="{OTHER.thumb}" data-src="{OTHER.thumb}" /></a>
				</div>
				<meta itemprop="datePublished" content="{OTHER.datePublished}" />
			</div>
            <p><strong>{OTHER.name}</strong></p>
		</div>
		<!-- END: loop_album -->	
	<div class="clear"></div>
	</div>
</div>
<!-- END: main -->