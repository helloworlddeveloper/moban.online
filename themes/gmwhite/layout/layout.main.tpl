<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
    <div class="bg-main-home">
    	<div class="container">
			<div class="row">
				<div class="col-sm-8 col-xs-8 item" style="border-right: 1px dotted #ccc;">
					<a href="/shops/">Tất cả sản phẩm</a>
				</div>

				<div class="col-sm-8 col-xs-8 item" style="border-right: 1px dotted #ccc;">
					<a href="/shops/blockcat/san-pham-noi-bat/">Sản phẩm nổi bật</a>
				</div>

				<div class="col-sm-8 col-xs-8 item">
					<a href="/shops/blockcat/san-pham-khuyen-mai/">Sản phẩm Khuyến mãi</a>
				</div>
			</div>
        </div>
    </div>
	<div class="container" id="body">
            <div class="row">
            	<div class="col-md-24">
					<div class="sanpham">[TOP]</div>
            		{MODULE_CONTENT}
            	</div>
            </div>
	</div>
<div>[FULL_WEB_BLOCK]</div>
<div class="container">
	<div class="col-md-12" align="right">
		[BLOCK_VIDEO_HOME]
	</div>
	<div class="col-md-12" align="left">
		[BLOCK_IMAGE_HOME]
	</div>
</div>
<div class="container">
	<div class="row myphamnews">
		[BOTTOM]
	</div>
</div>
<!--
<div class="pagebt">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="panel-body">[BLOCK_MAIN_1]</div>
			</div>
			<div class="col-md-8">
				<div class="panel-body">
					<div class=" bottom11">
						[BLOCK_MAIN_2]
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel-body">
					[BLOCK_MAIN_3]
				</div>
			</div>
		</div>
	</div>
</div>
-->
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->