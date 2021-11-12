<!-- BEGIN: main -->
<script type="text/javascript" data-show="after">
$(document).ready(function() {
    window.print();
});
</script>

<style type="text/css">
body {
	font-size: 12px;
	background: #fff;
}
/* Header
-----------------------------------------------------------------------------*/
#header {
	height: 52px;
}

#header .logo {
	padding: 0 0 0 10px;
	float: left;
}

#header .menu{
	padding-left: 20px;
	text-align: center;
}
.header-name{
	font-size: 16px;
}
.title-name{
	font-size: 20px;
}
.clear{
	padding-top: 10px;
	clear: both;
}
table tr{
	border: none !important;
}
table tr td{
	padding: 5px 0;
}
</style>
<div id="print">
	<header id="header" class="row">
		<div style="width: 100%">
			<div class="logo">
				<a title="GM White">
					<img class="logo-md" alt="GM White" src="{logo_site}" height="80px">
				</a>
			</div>
			<div class="menu">
				<div class="header-name">CÔNG TY CỔ PHẦN TẬP ĐOÀN CASH 13</div>
				<div class="title-name">{INFO_TITLE}</div>
			</div>
		</div>
	</header>
	<div style="width: 100%; text-align: center">
		<p>{ngay_xuat_kho}</p>
        <p style="font-size: 15px">{LANG.order_code}: <strong>{DATA_ORDER.order_code}</strong></p>
	</div>
	<table class="rows2">
		<tr>
			<td>
				<table>
					<tr>
						<td width="130px">{LANG.order_name}</td>
						<td>: <strong>{DATA_ORDER.order_name}</strong></td>
					</tr>
					<tr>
						<td>{LANG.order_email}</td>
						<td>: {DATA_ORDER.order_email}</td>
					</tr>
					<tr>
						<td>{LANG.order_phone}</td>
						<td>: {DATA_ORDER.order_phone}</td>
					</tr>
					<tr>
						<td valign="top">{LANG.order_address}</td>
						<td valign="top">: {DATA_ORDER.order_address}</td>
					</tr>
					<tr>
						<td>{LANG.order_date}</td>
						<td>: {dateup} {LANG.order_moment} {moment}</td>
					</tr>
				</table>
			</td>
			<td width="100px" valign="top" align="center">
			</td>
		</tr>
	</table>

	<table class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th align="center" width="30px">{LANG.stt}</th>
			<th>{LANG.product_title}</th>
			<th>{LANG.content_product_code}</th>
			<th class="price text-right form-tooltip">
                {LANG.product_price}
			</th>
			<!-- BEGIN: noadmin -->
			<th class="text-center" width="100px">SL</th>
			<!-- END: noadmin -->
			<!-- BEGIN: admin -->
			<th class="text-center" width="100px">Kho cổ đông</th>
			<th class="text-center" width="100px">Kho cty</th>
			<!-- END: admin -->
			<!-- BEGIN: product_gift -->
			<th style="width: 90px">{LANG.product_gift}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.product_gift_note}">&nbsp;</span></th>
			<!-- END: product_gift -->
			<th>{LANG.cart_unit}</th>
			<th class="text-right">{LANG.cart_price_total}</th>
		</tr>
		</thead>
		<tbody>
		<!-- BEGIN: rows -->
		<tr>
			<td align="center">{DATA.stt}</td>
			<td>{DATA.title} <span class="money">{DATA.isgift}</span></td>
			<td>{DATA.code}</td>
			<td class="money" align="right"><strong>{DATA.price_order}</strong></td>
			<td align="center">{DATA.order_number}</td>
			<!-- BEGIN: admin -->
			<td class="text-center" width="100px">{DATA.num_com}</td>
			<!-- END: admin -->
			<!-- BEGIN: product_gift --><td align="center">{DATA.order_numbergif}</td><!-- END: product_gift -->
			<td>{DATA.unit_product}</td>
			<td class="text-right money"><strong>{DATA.total_price}</strong></td>
		</tr>
		<!-- END: rows -->
		</tbody>
	</table>
    <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12">
			<em>{LANG.cart_note} : {DATA.order_note}</em>
		</div>
		<div class="clearfix">
			<table class="table text-right">
				<tr>
					<td>{LANG.cart_total_print}</td>
					<td><span class="money" id="price_total_fomart">{price_total_no_discount}</span></td>
				</tr>
				<!-- BEGIN: saleoff -->
				<tr>
					<td>{LANG.discount_price}</td>
					<td><span class="money" id="price_total_fomart"> {total_sale}</td>
				</tr>
				<!-- END: saleoff -->
				<!-- BEGIN: price_payment -->
				<tr>
					<td>{LANG.price_payment}</td>
					<td><span class="money">{price_payment_fomart}</span></td>
				</tr>
				<!-- END: price_payment -->
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
				<tr>
					<td>{LANG.total_money_text}</td>
					<td><span class="money" id="cart_total_fomart">{price_total_fomart_text}</span></td>
				</tr>
			</table>
			<div class="clear"></div>
			<!-- BEGIN: cty -->
			<table class="table" style="width: 100%;text-align: center;font-weight: bold;">
				<tr>
					<td style="width: 20%;border:none">GIÁM ĐỐC</td>
					<td style="width: 20%;border:none">ĐẦU NHÁNH</td>
					<td style="width: 20%;border:none">THỦ KHO</td>
					<td style="width: 20%;border:none">NGƯỜI LẬP PHIẾU</td>
				</tr>
				<tr>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
				</tr>
			</table>
			<!-- END: cty -->
			<!-- BEGIN: nocty -->
			<table class="table" style="width: 100%;text-align: center;font-weight: bold;">
				<tr>
					<td style="width: 20%;border:none">ĐẦU NHÁNH</td>
					<td style="width: 20%;border:none">NGƯỜI GIAO HÀNG</td>
					<td style="width: 20%;border:none">THỦ KHO</td>
					<td style="width: 20%;border:none">NGƯỜI NHẬN HÀNG</td>
					<td style="width: 20%;border:none">NGƯỜI LẬP PHIẾU</td>
				</tr>
				<tr>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
					<td style="padding-top: 60px;border:none"></td>
				</tr>
			</table>
			<!-- END: nocty -->
		</div>
	</div>
</div>
<!-- END: main -->