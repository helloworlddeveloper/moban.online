<!-- BEGIN: main -->
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-24 col-sm-19">
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
			<div class="col-xs-24 col-sm-5">
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
<form class="form-inline" action="" method="post" name="fpost" id="numberout">
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
				<th class="text-center" width="60px">{LANG.order_product_numbers_out}</th>
				<th style="width: 100px">{LANG.xuatthem}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.xuatthem_note}">&nbsp;</span></th>
				<th>{LANG.order_product_unit}</th>
				<th class="text-right">{LANG.order_product_price_total}</th>
			</tr>
			</thead>
			<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{PDATA.stt}</td>
				<td class="prd"><a href="javascript:void(0);" data-href="{PDATA.linkview}" class="openPopup">{PDATA.title}</a></td>
				<td class="text-center">{PDATA.code}</td>
				<td class="amount text-center">{PDATA.product_price}</td>
				<td class="amount text-center">{PDATA.product_number_com}</td>
				<td class="amount text-center">{PDATA.product_number_out}</td>
				<!-- BEGIN: xuathang --><td align="center"><input name="number_out[{PDATA.id}]" type="text" class="form-control"></td><!-- END: xuathang -->
				<!-- BEGIN: noxuathang --><td>Đã xuất đủ</td><!-- END: noxuathang -->
				<td class="unit">{PDATA.unit_title}</td>
				<td class="money" align="right"><strong>{PDATA.product_price_total}</strong></td>
			</tr>
			<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<p class="text-right">{LANG.discount_number}: <strong id="total">{DATA.saleoff}</strong></p>
<p class="text-right">{LANG.order_total}: <strong id="total">{total}</strong></p>
<p class="text-right">{LANG.total_payment}: <strong id="total">{DATA.price_payment_fomart}</strong></p>
<p class="text-right">{LANG.total_payment_no}: <strong id="total">{payment_amount}</strong></p>
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
		<input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save">
		<!-- BEGIN: onpay -->
        {LANG.input_price_order}
		<input type="text" onkeyup="this.value=FormatNumber(this.value);" class="form-control" placeholder="{LANG.payment_amount}" value="{payment_amount}" name="payment_amount">
		<input class="btn btn-primary" type="button" value="{LANG.order_submit_pay}" id="click_pay">
		<br><br>
		<!-- END: onpay -->
		<!-- BEGIN: feeship -->
        {LANG.input_feeship_order}
		<input type="text" onkeyup="this.value=FormatNumber(this.value);" class="form-control" placeholder="{LANG.payment_amount}" value="{feeship}" name="payment_amount">
		<input class="btn btn-primary" type="button" value="{LANG.order_submit_ship}" id="click_ship">
		<!-- END: feeship -->
		<!-- BEGIN: unpay -->
		<input class="btn btn-danger" type="button" value="{LANG.order_submit_unpay}" id="click_pay">
		<!-- END: unpay -->
		<!-- BEGIN: show_submit -->
		<input class="btn btn-success" type="button" value="{LANG.submit_numberout}" id="click_numberout">
		<!-- END: show_submit -->
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
                    data : 'save=1&payment_amount=' + $('input[name=payment_amount]').val(),
                    success : function(data) {
                        alert(data);
                        window.location = "{URL_BACK}";
                    }
                });
            }
        });
        $('#click_numberout').click(function(event) {
            event.preventDefault();
            if (confirm("{LANG.click_numberout_comfix}")) {
                $.ajax({
                    type : "POST",
                    url : '{URL_NUMBER_OUT}',
                    data : $( '#numberout' ).serialize(),
                    success : function(data) {
                        if( data == 'OK'){
                            window.location.href = window.location.href;
                        }
                        else{
                            alert(data);
                        }
                    }
                });
            }
        });
        $('#click_ship').click(function(event) {
            event.preventDefault();
            if (confirm("{LANG.order_submit_ship_comfix}")) {
                $.ajax({
                    type : "POST",
                    url : '{URL_ACTIVE_SHIP}',
                    data : 'save=1&payment_amount=' + $('input[name=payment_amount]').val(),
                    success : function(data) {
                        alert(data);
                        window.location = "{URL_BACK}";
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
                        window.location = "{URL_BACK}";
                    }
                });
            }
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Chi tiết các lần chuyển hàng</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
			</div>
		</div>

	</div>
</div>
<script>
    $(document).ready(function(){
        $('.openPopup').on('click',function(){
            var dataURL = $(this).attr('data-href');
            $('.modal-body').load(dataURL,function(){
                $('#myModal').modal({show:true});
            });
        });
    });
</script>
<!-- END: main -->
<!-- BEGIN: loadpopup -->
<table class="table table-striped table-bordered table-hover">
	<tr style="margin-bottom: 10px">
		<td width="130px">{LANG.order_products_name}:</td>
		<td><strong> {DATA.title} </strong></td>
	</tr>
	<tr>
		<td>{LANG.order_product_price}:</td>
		<td class="money">{DATA.price}</td>
	</tr>
	<tr>
		<td>{LANG.order_product_numbers}:</td>
		<td>{DATA.product_number}</td>
	</tr>
	<tr>
		<td>{LANG.order_product_unit}:</td>
		<td>{DATA.unit_title}</td>
	</tr>
</table>
<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
	<caption>
		Chi tiết các lần xuất hàng
	</caption>
	<thead>
	<tr>
		<th width="30px">{LANG.order_no_products}</th>
		<th class="text-center">{LANG.order_product_numbers_out}</th>
		<th class="text-right">{LANG.timeout}</th>
	</tr>
	</thead>
	<tbody>
	<!-- BEGIN: loop -->
	<tr>
		<td class="text-center">{DETAIL.stt}</td>
		<td class="text-center">{DETAIL.num_out}</td>
		<td align="right"><strong>{DETAIL.timeout}</strong></td>
	</tr>
	<!-- END: loop -->
	</tbody>
</table>
<!-- END: data -->
<!-- BEGIN: nodata -->
<div class="panel panel-default">
	<div class="panel-body">
		<div class="text-center"><strong>Sản phẩm chưa được xuất kho lần nào!</strong></div>
	</div>
</div>
<!-- END: nodata -->
<!-- END: loadpopup -->