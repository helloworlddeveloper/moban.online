<!-- BEGIN: main -->
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-24 col-sm-19 col-md-16">
				<table>
					<tr style="margin-bottom: 10px">
						<td width="130px">{LANG.order_name}:</td>
						<td><strong> {DATA.order_name} </strong></td>
					</tr>
					<tr>
						<td>{LANG.order_email}:</td>
						<td>{DATA.order_email}</td>
					</tr>
					<tr>
						<td>{LANG.order_phone}:</td>
						<td>{DATA.order_phone}</td>
					</tr>
					<tr>
						<td>{LANG.order_address}:</td>
						<td>{DATA.order_address}</td>
					</tr>
					<tr>
						<td>{LANG.order_date}:</td>
						<td>{dateup} {LANG.order_moment} {moment}</td>
					</tr>
				</table>
			</div>
			<div class="col-xs-24 col-sm-5 col-md-8">
				<div class="order_code text-center">
                    {LANG.order_code}
					<br>
					<span class="text_date"><strong>{DATA.order_code}</strong></span>
					<br>
					<span class="payment">{payment}</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
            {LANG.content_list}
		</caption>
		<thead>
		<tr>
			<th width="30px">{LANG.order_no_products}</th>
			<th>{LANG.order_products_name}</th>
			<th>{LANG.content_product_code}</th>
			<th class="text-center">{LANG.order_product_price}</th>
			<th class="text-center" width="60px">{LANG.order_product_numbers}</th>
			<!-- BEGIN: product_gift -->
			<th style="width: 90px">{LANG.product_gift}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.product_gift_note}">&nbsp;</span></th>
			<!-- END: product_gift -->
			<th>{LANG.order_product_unit}</th>
			<th class="text-right">{LANG.order_product_price_total}</th>
		</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="text-center">{PDATA.stt}</td>
			<td class="prd">{PDATA.title} <span class="red">{PDATA.isgift}</span></td>
			<td class="text-center">{PDATA.code}</td>
			<td class="amount text-center">{PDATA.product_price}</td>
			<td class="amount text-center">{PDATA.product_number}</td>
			<!-- BEGIN: product_gift --><td align="center">{PDATA.product_numbergif}</td><!-- END: product_gift -->
			<td class="unit">{PDATA.unit_title}</td>
			<td class="money" align="right"><strong>{PDATA.product_price_total}</strong></td>
		</tr>
		<!-- END: loop -->
		</tbody>
	</table>
</div>

<div class="clearfix">
	<table class="table text-right">
		<tr>
			<td>{LANG.order_total}</td>
			<td><span class="money" id="price_total_fomart">{total}</span></td>
		</tr>
		<!-- BEGIN: price_payment -->
		<tr>
			<td>{LANG.price_payment}</td>
			<td><span class="money">{DATA.price_payment_fomart}</span></td>
		</tr>
		<!-- END: price_payment -->
		<!-- BEGIN: total_sale -->
		<tr>
			<td>{LANG.discount_price}</td>
			<td><span class="money" id="discount_price">{total_sale}</span></td>
		</tr>
		<tr>
			<td>{LANG.cart_total}</td>
			<td><span class="money" id="cart_total_fomart">{total_sale_price}</span></td>
		</tr>
		<!-- END: total_sale -->
	</table>
	<div class="clear"></div>
</div>
<!-- BEGIN: order_note -->
<span style="font-style: italic;">{LANG.order_products_note} : {DATA.order_note}</span>
<!-- END: order_note -->
<table style="margin-top: 2px">
	<tr>
		<td><!-- BEGIN: admin_process --> - {LANG.order_admin_process} : {admin_process} <!-- END: admin_process --></td>
	</tr>
</table>
<div class="text-center">
	<form class="form-inline" action="" method="post" name="fpost" id="post">
		<input type="hidden" value="{order_id}" name="order_id" /><input type="hidden" value="1" name="save">
		<!-- BEGIN: onreturn -->
		<input type="text" class="form-control" value="{money_return}" placeholder="{LANG.money_return}" name="money_return">
		<input class="btn btn-primary" type="submit" value="{LANG.order_return}" id="order_return">
		<!-- END: onreturn -->
		<!-- BEGIN: unpay -->
		<input class="btn btn-danger" type="button" value="{LANG.order_submit_unpay}" id="click_pay">
		<!-- END: unpay -->
        <!-- BEGIN: onpay -->
        {LANG.input_price_order}
		<input type="text" onkeyup="this.value=FormatNumber(this.value);" class="form-control" placeholder="{LANG.payment_amount}" value="{payment_amount}" name="payment_amount" />
		<input class="btn btn-primary" type="button" value="{LANG.order_submit_pay}" id="click_pay">
		<!-- END: onpay -->
		<!-- BEGIN: active_order -->
		<input class="btn btn-success" type="button" value="{LANG.order_avaible}" id="order_avaible">
		<!-- END: active_order -->
		<input class="btn btn-info" type="button" value="{LANG.order_print}" id="click_print">
	</form>
</div>
<!-- BEGIN: transaction -->
<table class="table table-striped table-bordered table-hover">
	<caption>
        {LANG.history_transaction}
	</caption>
	<thead>
	<tr>
		<th width="30px">&nbsp;</th>
		<th>{LANG.payment_time}</th>
		<th>{LANG.user_payment}</th>
		<th>{LANG.status}</th>
		<th class="text-right">{LANG.order_total}</th>
		<th class="text-right">{LANG.transaction_time}</th>
	</tr>
	</thead>
	<tbody>
	<!-- BEGIN: looptrans -->
	<tr>
		<td class="text-center" width="30px">{DATA_TRANS.a}</td>
		<td>{DATA_TRANS.payment_time}</td>
		<td><a href="{DATA_TRANS.link_user}">{DATA_TRANS.payment}</a></td>
		<td>{DATA_TRANS.transaction}</td>
		<td align="right">{DATA_TRANS.payment_amount}</td>
		<td align="right">{DATA_TRANS.transaction_time}</td>
	</tr>
	<!-- END: looptrans -->
	</tbody>
	<!-- BEGIN: checkpayment -->
	<tfoot>
	<tr>
		<td colspan="8" align="right"><a href="{LINK_CHECK_PAYMENT}">{LANG.checkpayment}</a></td>
	</tr>
	</tfoot>
	<!-- END: checkpayment -->
</table>
<!-- END: transaction -->
<script type="text/javascript">
    $(function() {
        $('#click_submit').click(function(event) {
            event.preventDefault();
            if (confirm("{LANG.order_submit_comfix}")) {
                $('#post').submit();
            }
        });
        $('#click_print').click(function(event) {
            event.preventDefault();
            nv_open_browse('{LINK_PRINT}', '', 640, 300, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
            return false;
        });
        $('#click_pay').click(function(event) {
            event.preventDefault();
            if (confirm("{LANG.order_submit_pay_comfix}")) {
                $.ajax({
                    type : "POST",
                    url : '{URL_ACTIVE_PAY}',
                    data : 'save=1&action=pay&payment_amount=' + $('input[name=payment_amount]').val(),
                    success : function(data) {
                        alert(data);
                        window.location.href = window.location.href;
                    }
                });
            }
        });
        $('#order_avaible').click(function(event) {
            event.preventDefault();
            if (confirm("{LANG.order_submit_avaible_comfix}")) {
                $.ajax({
                    type : "POST",
                    url : '{URL_AVAIBLE}',
                    data : 'save=1&avaible=1',
                    success : function(data) {
                        alert(data);
                        window.location.href = window.location.href;
                    }
                });
            }
        });
        $('#order_return').click(function(event) {
            event.preventDefault();
            var money_return = $('input[name=money_return]').val();
            if( money_return == ''){
                $('input[name=money_return]').focus();
                return;
            }
            if (confirm("{LANG.order_submit_return_comfix}")) {
                $.ajax({
                    type : "POST",
                    url : '{URL_ACTIVE_RETURN}',
                    data : 'save=1&money_return=' + money_return,
                    success : function(data) {
                        alert(data);
                        window.location.href = window.location.href;
                    }
                });
            }
        });
    });
</script>
<!-- END: main -->