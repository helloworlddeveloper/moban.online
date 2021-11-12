<!-- BEGIN: main -->
<div id="products" class="clearfix sanpham">
    <!-- BEGIN: displays -->
    <div class="form-group form-inline pull-right">
        <label class="control-label">{LANG.displays_product}</label>
        <select name="sort" id="sort" class="form-control input-sm" onchange="nv_chang_price();">
            <!-- BEGIN: sorts -->
                <option value="{key}" {se}> {value}</option>
            <!-- END: sorts -->
        </select>
    </div>
    <div class="clearfix">&nbsp;</div>
    <!-- END: displays -->

    <!-- BEGIN: items -->
    <div class="col-sm-12 col-md-{num}">
        <div class="item">
            <div class="bigimg">
                <a class="featured_image_link" href="{LINK}" title="{TITLE}"><img src="{IMG_SRC}" alt="{TITLE}" <!-- BEGIN: tooltip_js -->data-content='{hometext}' data-rel="tooltip" data-img="{IMG_SRC}"<!-- END: tooltip_js -->class="aligncenter wp-post-image img-thumbnail"></a>
            </div>
            <div class="floadright product-item">
                <h3 class="tieude"><a href="{LINK}" rel="bookmark" title="{TITLE}">{TITLE}</a></h3>
                <div class="col-md-24">
                    
                    <!-- BEGIN: price -->
                    <strong class="best_price">
                        <!-- BEGIN: discounts -->
                        <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
                        <span class="best_price_space">{PRICE.sale_format} {PRICE.unit}</span>
                        <!-- END: discounts -->

                        <!-- BEGIN: no_discounts -->
                        <span class="best_price_space">{PRICE.price_format} {PRICE.unit}</span>
                        <!-- END: no_discounts -->
                    </strong>
                    <!-- END: price -->
                    <!-- BEGIN: contact -->
                    <span class="price">{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span></span>
                    <!-- END: contact -->
                </div>
                <div style="padding-top: 15px" class="col-md-12 col-sm-12 col-xs-12">
                    <!-- BEGIN: product_code -->
                    <p><strong>{LANG.product_code}:</strong> <span class="best_msp_space"></span>{PRODUCT_CODE}</p>
                    <!-- END: product_code -->
                </div>
                <!-- BEGIN: order -->
                <a href="javascript:void(0)" id="{ID}" title="{title_pro}" onclick="cartorder(this, {GROUP_REQUIE}, '{LINK}')"><button type="button" class="btn btn-primary btnAddToCart btn-xs">{LANG.add_product}</button></a>
                <!-- END: order -->
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!-- END: items -->
</div>

<!-- BEGIN: modal_loaded -->
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
<!-- END: modal_loaded -->

<!-- BEGIN: pages -->
<div class="text-center">
    {generate_page}
</div>
<!-- END: pages -->
<div class="msgshow" id="msgshow">&nbsp;</div>

<!-- END: main -->
