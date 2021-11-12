<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<select class="form-control" name="vehicle">
						<option value="">-- {LANG.vehicle} --</option>
						<!-- BEGIN: select_vehicle -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_vehicle -->
					</select>
				</div>
			</div>
			<div class="col-xs-24 col-md-5">
				<div class="form-group">
					<select class="form-control" name="localtion_start">
						<option value="">-- {LANG.localtion_start} --</option>
						<!-- BEGIN: localtion_start -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: localtion_start -->
					</select>
				</div>
			</div>
			<div class="col-xs-24 col-md-5">
				<div class="form-group">
					<select class="form-control" name="localtion_end">
						<option value="">-- {LANG.localtion_end} --</option>
						<!-- BEGIN: localtion_end -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: localtion_end -->
					</select>
				</div>
			</div>
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control datepicker" value="{SEARCH.starttime}" type="text" name="starttime" readonly="readonly" placeholder="{LANG.starttime}" /> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control datepicker" value="{SEARCH.endtime}" type="text" name="endtime" readonly="readonly" placeholder="{LANG.endtime}" /> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-2">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" name="submit" />
				</div>
			</div>
		</div>
	</form>
</div>
<!-- BEGIN: data -->
<span class="pull-right m-bottom"><strong>{LANG.total}: {sum}</strong></span>
<div class="clearfix"></div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="w50 text-center">{LANG.stt}</th>
				<th class="w100">{LANG.car_number_plate}</th>
				<th>{LANG.all_customer_price}</th>
				<th>{LANG.all_commodity_price}</th>
				<th class="text-center">{LANG.all_cost_price}</th>
                <th>{LANG.all_loinhuan_price}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{stt}</td>
				<td>{VALUE.car_number_plate}</td>
				<td>{CUSTOMER.total_price}</td>
				<td align="right">{COMMODITY.total_price}</td>
				<td class="text-center">{COST.price}</td>
                <td>{all_loinhuan_price}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: data -->
<!-- BEGIN: nodata -->
<div class="well">{LANG.nodata}</div>
<!-- END: nodata -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
	$(".datepicker").datepicker({
			dateFormat: "dd/mm/yy",
			changeMonth: !0,
			changeYear: !0,
			showOtherMonths: !0,
			showOn: "focus",
			yearRange: "-90:+0"
	});
</script>

<!-- END: main -->