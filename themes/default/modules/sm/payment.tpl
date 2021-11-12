<!-- BEGIN: main -->
<div class="block clearfix">
	<div class="row"><br>
        <p style="font-size: 17px">{LANG.info_order}</p>
		<hr>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-xs-14">
				<div class="row">
					<div class="col-md-10">
						<strong>{LANG.order_name}:</strong>
					</div>
					<div class="col-md-14">
						{DATA_ORDER.order_name}
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						<strong>{LANG.order_email}:</strong>
					</div>
					<div class="col-md-14">
						{DATA_ORDER.order_email}
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						<strong>{LANG.order_phone}:</strong>
					</div>
					<div class="col-md-14">
						{DATA_ORDER.order_phone}
					</div>
				</div>

				<!-- BEGIN: order_address -->
				<div class="row">
					<div class="col-md-10">
						<strong>{LANG.order_address}:</strong>
					</div>
					<div class="col-md-14">
						{DATA.order_address}
					</div>
				</div>
				<!-- END: order_address -->

				<div class="row">
					<div class="col-md-10">
						<strong>{LANG.order_date}:</strong>
					</div>
					<div class="col-md-14">
						{dateup} {LANG.order_moment} {moment}
					</div>
				</div>
			</div>
			<div class="col-xs-10">
				<div class="text-center">
					{LANG.order_code}
					<br>
					<span>{DATA_ORDER.order_code}</span>
					<br>
					<span class="payment">{DATA_ORDER.transaction_name}</span>
					<a href="{url_print}" title="" id="click_print" class="btn btn-success hidden-xs" style="margin-top:5px"><em class="fa fa-print">&nbsp;&nbsp;{LANG.order_print}</em></a>
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
				<th align="center" width="30px">{LANG.stt}</th>
				<th>{LANG.product_title}</th>
				<th class="price text-right form-tooltip">
                    {LANG.product_price}
				</th>
				<th class="text-center" width="100px">{LANG.cart_numbers}</th>
				<!-- BEGIN: product_gift -->
				<th class="text-center"  style="width: 90px">{LANG.product_gift}</th>
				<!-- END: product_gift -->
				<th>{LANG.cart_unit}</th>
				<th class="text-right">{LANG.cart_price_total}</th>
			</tr>
			</thead>
			<tbody>
			<!-- BEGIN: rows -->
			<tr>
				<td align="center">{DATA.stt}</td>
				<td>{DATA.title}</td>
				<td class="money" align="right"><strong>{DATA.price_order}</strong></td>
				<td align="center">{DATA.order_number}</td>
				<!-- BEGIN: product_gift --><td align="center">{DATA.order_numbergif}</td><!-- END: product_gift -->
				<td>{DATA.unit_product}</td>
				<td class="text-right money"><strong>{DATA.total_price}</strong></td>
			</tr>
			<!-- END: rows -->
			</tbody>
		</table>
	</div>
</div>
<table class="table text-right">
	<tr>
		<td>{LANG.cart_total_print}</td>
		<td><span class="money" id="price_total_fomart">{price_total_no_discount}</span></td>
	</tr>
	<!-- BEGIN: saleoff -->
	<tr>
		<td>{LANG.discount_price}</td>
		<td><span class="money" id="price_total_fomart"> {SALEOFF}</td>
	</tr>
	<!-- END: saleoff -->
	<!-- BEGIN: discount -->
	<tr>
		<td>{LANG.discount_price}</td>
		<td><span class="money" id="discount_price">{price_total_discount_fomart}</span> <i>({discount}%)</i></td>
	</tr>
	<!-- END: discount -->
	<tr>
		<td>{LANG.cart_total}</td>
		<td><span class="money" id="cart_total_fomart">{price_total_fomart}</span></td>
	</tr>
</table>
<script type="text/javascript" data-show="after">
	$(function() {

		$('#click_print').click(function(event) {
			var href = $(this).attr("href");
			event.preventDefault();
			nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
			return false;
		});
	});
</script>
<!-- END: main -->