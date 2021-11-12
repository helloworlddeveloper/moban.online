<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div class="panel-body">
	<div class="row">
		<label class="col-sm-6 col-md-8 control-label">{LANG.code_agency}:</label>
		<div class="col-sm-18 col-md-16">
            {DATA_USER.code}
		</div>
	</div>
	<div class="row">
		<label class="col-sm-6 col-md-8 control-label">{LANG.order_name}:</label>
		<div class="col-sm-18 col-md-16">
            [{DATA_USER.agencytitle}]&nbsp;{DATA_USER.fullname}
		</div>
	</div>
	<div class="row">
		<label class="col-sm-6 col-md-8 control-label">Email:</label>
		<div class="col-sm-18 col-md-16">
            {DATA_USER.email}
		</div>
	</div>
	<div class="row">
		<label class="col-sm-6 col-md-8 control-label">{LANG.order_address}:</label>
		<div class="col-sm-18 col-md-16">
            {DATA_USER.datatext.address}
		</div>
	</div>
</div>
<div class="well">
	<form action="{NV_BASE_SITEURL}index.php" class="form-inline" method="get" id="search_form">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<input type="hidden" name="userid" value="{userid}" />
		<input type="hidden" name="checkss" value="{checkss}" />
		<input type="hidden" name="act" value="{SEARCH.act}" />
		<div class="row">
			<div class="col-xs-12 col-md-10">
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

<ul class="nav nav-tabs">
	<li class="{active_all}"><a href="{LINK_VIEW}&act=all">Thống kê tổng hợp</a></li>
	<li class="{active_detail}"><a href="{LINK_VIEW}&act=detail">Thống kê chi tiết</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade in{active_all}">
		<div class="panel-body">
			<!-- BEGIN: all -->
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>{LANG.product_title}</th>
						<th class="text-center">Tồn đầu</th>
						<th class="text-center">Nhập</th>
						<th class="text-center">Xuất</th>
						<th class="text-center">Tồn cuối</th>
					</tr>
					</thead>
					<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td>{VIEW.product_title}</td>
						<td class="text-center">{VIEW.begin}</td>
						<td class="text-center">{VIEW.quantity_in}</td>
						<td class="text-center">{VIEW.quantity_out}</td>
						<td class="text-center">{VIEW.end}</td>
					</tr>
					<!-- END: loop -->
					</tbody>
				</table>
			</div>
			<!-- END: all -->
		</div>
	</div>
	<div class="tab-pane fade-in{active_detail}">
		<div class="panel-body">
			<!-- BEGIN: chitiet -->
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>{LANG.product_title}</th>
						<th class="text-center">{LANG.addtime}</th>
						<th class="text-center">{LANG.quantity_befor}</th>
						<th class="text-center">{LANG.quantity_in}</th>
						<th class="text-center">{LANG.quantity_out}</th>
						<th class="text-center">{LANG.quantity_after}</th>
						<th class="text-center">{LANG.price_in}</th>
						<th class="text-center">{LANG.price_out}</th>
					</tr>
					</thead>
					<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td>{VIEW.title_product}</td>
						<td class="text-center">{VIEW.addtime}</td>
						<td class="text-center">{VIEW.quantity_befor}</td>
						<td class="text-center">{VIEW.quantity_in}</td>
						<td class="text-center">{VIEW.quantity_out}</td>
						<td class="text-center">{VIEW.quantity_after}</td>
						<td class="text-center">{VIEW.price_out}</td>
						<td class="text-center">{VIEW.price_in}</td>
					</tr>
					<!-- END: loop -->
					</tbody>
					<tfoot>
					<tr>
						<td colspan="3" class="text-right"><strong>{LANG.sum}</strong></td>
						<td class="text-center"><span class="money">{SUM.quantity_total_in}</span></td>
						<td class="text-center"><span class="money">{SUM.quantity_total_out}</span></td>
						<td></td>
						<td class="text-center"><span class="money">{SUM.price_total_out}</span></td>
						<td class="text-center"><span class="money">{SUM.price_total_in}</span></td>
					</tr>
					<!-- BEGIN: generate_page -->
					<tr class="text-center">
						<td colspan="5">{NV_GENERATE_PAGE}</td>
					</tr>
					<!-- END: generate_page -->
					</tfoot>
				</table>
			</div>
			<!-- END: chitiet -->
		</div>
	</div>
</div>
<script type='text/javascript'>
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
<!-- END: main -->