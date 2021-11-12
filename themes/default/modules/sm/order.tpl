<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well col-md-16">
	<form action="{NV_BASE_SITEURL}index.php" method="GET">
		<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label class="sr-only">{LANG.order_code}</label>
					<input type="text" name="order_code" value="{SEARCH.order_code}" class="form-control" placeholder="{LANG.order_code}">
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
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
			<div class="col-xs-12 col-md-6">
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
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label class="sr-only">{LANG.order_mobile}</label>
					<input type="text" name="order_phone" value="{SEARCH.order_phone}" class="form-control" placeholder="{LANG.order_phone}">
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label class="sr-only">{LANG.order_name}</label>
					<input type="text" name="order_name" value="{SEARCH.order_name}" class="form-control" placeholder="{LANG.order_name}">
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label class="sr-only">{LANG.order_email}</label>
					<input type="email" name="order_email" value="{SEARCH.order_email}" class="form-control" placeholder="{LANG.order_email}">
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label class="sr-only">{LANG.order_payment}</label>
					<select class="form-control" name="order_payment">
						<option value="">{LANG.order_payment}</option>
						<!-- BEGIN: transaction_status -->
						<option value="{TRAN_STATUS.key}"{TRAN_STATUS.selected}>{TRAN_STATUS.title}</option>
						<!-- END: transaction_status -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input type="hidden" name ="checkss" value="{CHECKSESS}" />
					<input type="submit" class="btn btn-primary" name="search" value="{LANG.search}" />
					<a class="btn btn-success" href="javascript:voi(0);" id="export_all">{LANG.export_all}</a>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="col-md-8">
	<table class="table table-striped table-bordered">
		<tbody class="text-right">
		<tr>
			<th>{LANG.siteinfo_order}:</th>
			<td><strong style="color: red">{ORDER_INFO.num_items}</strong></td>
		</tr>
		<tr>
			<th>{LANG.order_total_out}:</th>
			<td><strong style="color: red">{ORDER_INFO.sum_price_out}</strong></td>
		</tr>
		<tr>
			<th>{LANG.order_total_in}:</th>
			<td><strong style="color: red">{ORDER_INFO.sum_price_in}</strong></td>
		</tr>
		</tbody>
	</table>
</div>
<div class="clearfix"></div>
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th><strong>{LANG.order_code}</strong></th>
			<th><strong>{LANG.order_time}</strong></th>
			<th><strong>{LANG.customer_order}</strong></th>
			<th align="right"><strong>{LANG.order_total}</strong></th>
			<th><strong>{LANG.order_payment}</strong></th>
			<th width="130px" class="text-center"><strong>{LANG.function}</strong></th>
		</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
		<tr id="{DATA.order_id}" <!-- BEGIN: bgview -->class="warning"<!-- END: bgview -->>
		<td><a href="{link_view}" title="">{DATA.order_code}{DATA.ordertype_title}</a> {DATA.shipcode}</td>
		<td>{DATA.order_time}</td>
		<td>{DATA.order_name}</td>
		<td align="right">{DATA.order_total} {DATA.unit_total}</td>
		<td>{DATA.status_payment}</td>
		<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{link_view}" title="">{LANG.view}</a><!-- BEGIN: delete --> &nbsp;-&nbsp;<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{link_del}" class="delete" title="">{LANG.del}</a><!-- END: delete --></td>
		</tr>
		<!-- END: row -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6">{PAGES}</td>
			</tr>
		</tfoot>
	</table>
</div>
<script type='text/javascript'>
    $(function() {
        $('#checkall').click(function() {
            $('input:checkbox').each(function() {
                if( !$(this).attr("disabled") )
                {
                    $(this).attr('checked', 'checked');
                }
            });
            return false;
        });
        $('#uncheckall').click(function() {
            $('input:checkbox').each(function() {
                $(this).removeAttr('checked');
            });
            return false;
        });
        $('#delall').click(function() {
            if (confirm(nv_is_del_confirm[0])) {
                var listall = [];
                $('input.ck:checked').each(function() {
                    listall.push($(this).val());
                });
                if (listall.length < 1) {
                    alert("{LANG.prounit_del_no_items}");
                    return false;
                }
                $.ajax({
                    type : 'POST',
                    url : '{URL_DEL}',
                    data : 'listall=' + listall,
                    success : function(data) {
                        var r_split = data.split('_');
                        if( r_split[0] == 'OK' ){
                            window.location.href = window.location.href;
                        }
                        else{
                            alert( nv_is_del_confirm[2] );
                        }
                    }
                });
            }
            return false;
        });
        $('#export_all').click(function() {
            if (confirm('{LANG.nv_is_export_confirm}')) {
                $.ajax({
                    type : 'POST',
                    url : nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export',
                    data : 'sql_show={sql_show}',
                    dataType: "json",
                    success : function(response) {
                        if (response.status == "OK") {
                            $("#loading_bar").hide();
                            alert(response.mess);
                            window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
                        } else {
                            $("#loading_bar").hide();
                            alert(response.mess);
                            window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
                        }
                    }
                });
            }
            return false;
        });
        $('a.delete').click(function() {
            if (confirm("{LANG.prounit_del_confirm}")) {
                var href = $(this).attr('href');
                var id = $(this).parents().parents().attr('id');
                $.ajax({
                    type : 'POST',
                    url : href,
                    data : '',
                    success : function(data) {
                        $('#'+id).remove();
                    }
                });
            }
            return false;
        });

        $("#from, #to").datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            showOn : 'focus'
        });
        $('#to-btn').click(function(){
            $("#to").datepicker('show');
        });
        $('#from-btn').click(function(){
            $("#from").datepicker('show');
        });
    });
</script>
<!-- END: data -->
<!-- END: main -->