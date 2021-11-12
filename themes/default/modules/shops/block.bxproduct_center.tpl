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
	<div class="col-md-4 col-sm-6 col-xs-12 item">
		<div class="product-item">
			<a class="" href="{MYDOMAIN_MAIN}{DATA.link}">
				<img src="{DATA.src}" class="img-thumbnail product-mainpic" alt="{DATA.title}">
			</a>
			<div class="product-info">
				<p class="price_product">
					<span class="new-price">
						<!-- BEGIN: price -->
						<!-- BEGIN: discounts -->
								<span class="best_price_space show">
									{PRICE.sale_format} {PRICE.unit}
									<span class="discounts_money">{PRICE.price_format}</span>
								</span>
						<!-- END: discounts -->
						<!-- BEGIN: no_discounts -->
								<span class="best_price_space">{PRICE.price_format} {PRICE.unit}</span>
						<!-- END: no_discounts -->

						<!-- END: price -->
						<!-- BEGIN: contact -->
						<span class="best_price_space">{LANG.price_contact}</span>
						<!-- END: contact -->
					</span>
				</p>
				<h3><a href="{MYDOMAIN_MAIN}{DATA.link}" class="product-name">{DATA.title}</a></h3>
			</div>
		</div>
	</div>
	<!-- END: items -->
</div>
<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{LANG.add_product}</h4>
			</div>
			<div class="modal-body">
				<em class="fa fa-spinner fa-spin">&nbsp;</em>
			</div>
		</div>
	</div>
</div>
<div class="msgshow" id="msgshow">&nbsp;</div>
<!-- END: main -->

