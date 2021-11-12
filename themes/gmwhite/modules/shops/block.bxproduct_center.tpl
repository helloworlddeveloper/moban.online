<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script data-show="after" type="text/javascript" src="{LIB_PATH}js/jquery.bxslider.min.js"></script>
<link href="{LIB_PATH}css/jquery.bxslider.css" rel="stylesheet" />
<!-- END: lib -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.slider_{BLOCKID}').bxSlider({
            auto:{AUTO},
            mode:'{MODE}',
            speed:{SPEED},
            slideWidth:{WIDTH},
            slideMargin:{MARGIN},
            minSlides:{NUMVIEW},
            maxSlides:{NUMVIEW},
            moveSlides:{MOVE},
            pager:{PAGER},
            adaptiveHeight: true
        });
    });
</script>
<style>
	.bx-wrapper .bx-viewport{
		border: none;
		box-shadow:none;
	}
</style>
<div class="clearfix">
	<!-- BEGIN: items -->
	<div class="col-md-6 col-sm-6 col-xs-12 item">
		<div class="product-item">
			<a class="" href="{LINK}">
				<img src="{SRC_IMG}" class="img-thumbnail product-mainpic" alt="{TITLE}">
				<img src="{SRC_IMG}" class="img-thumbnail product-secondpic" alt="{TITLE}">
			</a>
		</div>
	</div>
	<!-- END: items -->
</div>

<!-- END: main -->

