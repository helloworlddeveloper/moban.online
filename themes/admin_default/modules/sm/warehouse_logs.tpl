<!-- BEGIN: main -->

<!-- BEGIN: list -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" class="form-inline" method="get" id="search_form">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<input type="hidden" name="userid" value="{userid}" />
		<input type="hidden" name="checkss" value="{checkss}" />
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<div class="input-group">
						<select class="form-control" name="product">
							<option value="0">--{LANG.search_product}--</option>
							<!-- BEGIN:product -->
							<option value="{PRODUCT.id}"{PRODUCT.sl}>{PRODUCT.title}</option>
							<!-- END:product -->
						</select>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<div class="input-group">
						<select class="form-control" name="depotid">
							<option value="0">--{LANG.select_depot}--</option>
							<!-- BEGIN:depot -->
							<option value="{DEPOT.id}"{DEPOT.sl}>{DEPOT.title}</option>
							<!-- END:depot -->
						</select>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<div class="input-group">
						<input type="text" name="from" id="from" value="{SEARCH.date_from}" class="form-control" placeholder="{LANG.date_from}" readonly="readonly">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="from-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<div class="input-group">
						<input type="text" name="to" id="to" value="{SEARCH.date_to}" class="form-control" placeholder="{LANG.date_to}" readonly="readonly">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="to-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="{LANG.search}" />
			</div>
		</div>
	</form>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col />
			<col class="w250" />
			<col class="w150" />
			<col class="w200" span="2" />
		</colgroup>
		<thead>
		<tr>
			<th>{LANG.product_title}</th>
			<th class="text-center">{LANG.quantity_befor}</th>
			<th class="text-center">{LANG.quantity_after}</th>
			<th class="text-center">{LANG.addtime}</th>
			<th class="text-center">{LANG.quantity_change}</th>
			<th class="text-center">{LANG.price_change}</th>
		</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>{VIEW.title_product}</td>
			<td class="text-center">{VIEW.quantity_befor}</td>
			<td class="text-center">{VIEW.quantity_after}</td>
			<td class="text-center">{VIEW.addtime}</td>
			<td class="text-center">{VIEW.quantity_change}</td>
			<td class="text-center">{VIEW.price_change}</td>
		</tr>
		<!-- END: loop -->
		</tbody>
		<tfoot>
		<tr>
			<td colspan="4" class="text-right"><strong>{LANG.sum}</strong></td>
			<td class="text-center"><span class="money">{SUM.quantity_change}</span></td>
			<td class="text-center"><span class="money">{SUM.price_total}</span></td>
		</tr>
		<!-- BEGIN: generate_page -->
		<tr class="text-center">
			<td colspan="5">{NV_GENERATE_PAGE}</td>
		</tr>
		<!-- END: generate_page -->
		</tfoot>
	</table>
</div>
<script type='text/javascript'>
    function nv_export_excel()
    {
        $('.exportexcel').attr('disabled', 'disabled');
        $('#loading_bar').html('<center><img src="'+ nv_base_siteurl + 'assets/images/load_bar.gif" alt="" />&nbsp;{LANG.waiting}...</center>');
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exportwarehouse&nocache=' + new Date().getTime(), 'order_id={order_id}', function(res) {
            var r_split = res.split('_');
            if(r_split[0] == 'OK'){
                $("#loading_bar").hide();
                alert(r_split[1]);
                $('.exportexcel').removeAttr('disabled');
                window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exportwarehouse&step=2';
            }else{
                $("#loading_bar").hide();
                alert(r_split[1]);
                $('.exportexcel').removeAttr('disabled');
            }
        });
    }
    $('.number_sale').keyup(function(){
        var obj = $(this).attr('data_id');
        save_price(obj);
    });
    $('.product_price').keyup(function(){
        var obj = $(this).attr('data_id');
        save_price(obj);
    });
    function save_price(obj){
        var product_price = $('#product_price_' + obj).val();
        product_price = product_price.split('.').join('');
        var total_price = $('#number_sale_' + obj).val() * product_price + '';
        $('input[name=total_price_' + obj + ']').val( total_price );
        $('#total_price_' + obj).html( FormatNumber(total_price));
        var tongloinhuan = total_price - ($('#number_sale_' + obj).val() * $('input[name=gianhap_' + obj + ']').val().split('.').join(''))
        $('#loinhuan_' + obj).html( FormatNumber(tongloinhuan + ''));
        $('input[name=loinhuan_' + obj + ']').val( tongloinhuan );
        var tonkho = $('input[name=quantity_' + obj + ']').val() - $('#number_sale_' + obj).val();
        $('#tonkho_' + obj).html( FormatNumber(tonkho + ''));
        $('input[name=tonkho_' + obj + ']').val( tonkho );
        var tientonkho = $('input[name=gianhap_' + obj + ']').val().split('.').join('') * tonkho;
        $('#tientonkho_' + obj).html( FormatNumber(tientonkho + ''));
        $('input[name=tientonkho_' + obj + ']').val( tientonkho );

        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=warehouse_logs&nocache=' + new Date().getTime(), 'update_warehouse&logid=' + obj + '&quantity_out=' + $('#number_sale_' + obj).val() + '&price_out=' + $('#product_price_' + obj).val(), function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                //alert(nv_is_change_act_confirm[2]);
            }
            clearTimeout(nv_timer);
            var parentid = parseInt(r_split[1]);
            nv_show_list_cat(parentid);
            return;
        });
    }
    $(function() {
        $("#from, #to").datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            showOn : 'focus'
        });
        $('#to-btn').click(function() {
            $("#to").datepicker('show');
        });
        $('#from-btn').click(function() {
            $("#from").datepicker('show');
        });
    });
</script>
<!-- END: list -->

<!-- BEGIN: view -->
<div class="panel panel-default">
	<div class="panel-body">
		<table class="table table-bordered">
			<col class="w200" />
			<tbody>
			<tr>
				<td class="text-right"><strong>{LANG.title}</strong></td>
				<td>{DATA.title}</td>
			</tr>
			<tr>
				<td class="text-right"><strong>{LANG.user_payment}</strong></td>
				<td>{DATA.full_name}</td>
			</tr>
			<tr>
				<td class="text-right"><strong>{LANG.warehouse_time}</strong></td>
				<td>{DATA.addtime}</td>
			</tr>
			<!-- BEGIN: note -->
			<tr>
				<td class="text-right"><strong>{LANG.content_note}</strong></td>
				<td>{DATA.note}</td>
			</tr>
			<!-- END: note -->
			</tbody>
		</table>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.warehouse_detail_info}</caption>
		<colgroup>
			<col class="w50" />
			<col />
			<col width="600" />
		</colgroup>
		<thead>
		<tr>
			<th class="text-center">{LANG.setting_stt}</th>
			<th>{LANG.name}</th>
			<th class="text-center">{LANG.warehouse_quantity}</th>
		</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="text-center">{LOGS.no}</td>
			<td><a href="{DATA.link}" title="{DATA.title}">{LOGS.title}</a></td>
			<td>
				<!-- BEGIN: product_number -->
				<div class="row">
					<div class="col-xs-8 text-center">

					</div>
					<div class="col-xs-8 text-center">
						<strong>{LANG.seller_num}</strong>
					</div>
					<div class="col-xs-8">
						<strong>{LANG.warehouse_price}</strong>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="col-xs-8 text-center">

					</div>
					<div class="col-xs-8 text-center">
						{LOGS.quantity}
					</div>
					<div class="col-xs-8">
						{LOGS.price} {LOGS.money_unit}
					</div>
				</div>
				<!-- END: product_number -->

				<!-- BEGIN: group -->
				<div class="row">
					<div class="col-xs-8 text-right">
						<strong>{LANG.warehouse_detail_group}</strong>
					</div>
					<div class="col-xs-8 text-center">
						<strong>{LANG.seller_num}</strong>
					</div>
					<div class="col-xs-8">
						<strong>{LANG.warehouse_price}</strong>
					</div>
				</div>
				<hr />
				<!-- BEGIN: loop -->
				<div class="row">
					<div class="col-xs-8 text-right">
						<!-- BEGIN: group_logs -->
						<label class="label label-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="{GROUP.parent_title}">{GROUP.title}</label>
						<!-- END: group_logs -->
					</div>
					<div class="col-xs-8 text-center">
						{G_LOGS.quantity}
					</div>
					<div class="col-xs-8">
						{G_LOGS.price} {G_LOGS.money_unit}
					</div>
				</div>
				<hr />
				<!-- END: loop -->
				<!-- END: group -->
			</td>
		</tr>
		<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: view -->

<!-- END: main -->