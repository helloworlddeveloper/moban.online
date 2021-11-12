<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<!-- BEGIN: view -->
<div class="well">
	<form action="{NV_BASE_SITEURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-24 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_title}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
				</div>
			</div>
		</div>
	</form>
</div>
<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w50 text-center">{LANG.number}</th>
					<th>{LANG.title_itinerary}</th>
					<th>{LANG.time_start}</th>
					<th>{LANG.time_end}</th>
					<th>{LANG.localtion_start}</th>
					<th>{LANG.localtion_end}</th>
					<th>{LANG.vehicle}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="20">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr onclick="document.location = '{VIEW.link_view}#view';" class="pointer">
					<td class="text-center">{VIEW.number}</td>
					<td><a href="{VIEW.link_view}#view">{VIEW.title_itinerary}</a></td>
					<td>{VIEW.time_start}</td>
					<td>{VIEW.time_end}</td>
					<td>{VIEW.localtion_start}</td>
					<td>{VIEW.localtion_end}</td>
					<td>{VIEW.vehicle}</td>
					<td class="text-center"><a href="{VIEW.link_view}#view" data-toggle="tooltip" data-original-title="{LANG.view}"><em class="fa fa-search fa-lg">&nbsp;</em></a> - <a href="{VIEW.link_edit}#edit" data-toggle="tooltip" data-original-title="{LANG.edit}"><i class="fa fa-edit fa-lg">&nbsp;</i></a> - <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<div class="col-sm-24 col-md-12">
					<label class="control-label"><strong>{LANG.title_itinerary}</strong> <span class="red">(*)</span></label> <input class="form-control" type="text" name="title_itinerary" value="{ROW.title_itinerary}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
				</div>
				<div class="col-sm-24 col-md-12">
					<label class="control-label"><strong>{LANG.vehicle}</strong> <span class="red">(*)</span></label> <select class="form-control" name="vehicle">
						<option value="">---</option>
						<!-- BEGIN: select_vehicle -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_vehicle -->
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-19 col-md-12">
					<div class="row">
						<label class="col-sm-5 col-md-24 control-label text_left"><strong>{LANG.time_start}</strong> <span class="red">(*)</span></label>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="input-group">
								<input placeholder="00:00" class="form-control timepicker" type="text" name="time_start" value="{ROW.time_start}" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<em class="fa fa-clock-o fa-fix"> </em>
									</button>
								</span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="input-group">
								<input placeholder="01/01/2018" class="form-control" type="text" name="date_start" value="{ROW.date_start}" id="time_start" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<em class="fa fa-calendar fa-fix"> </em>
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-19 col-md-12">
					<div class="row">
						<label class="col-sm-5 col-md-24 control-label text_left"><strong>{LANG.time_end}</strong> <span class="red">(*)</span></label>
						<div class="col-sm-12 col-md-12">
							<div class="input-group">
								<input placeholder="00:00" class="form-control timepicker" type="text" name="time_end" value="{ROW.time_end}" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<em class="fa fa-clock-o fa-fix"> </em>
									</button>
								</span>
							</div>
						</div>
						<div class="col-sm-12 col-md-12">
							<div class="input-group">
								<input placeholder="01/01/2018" class="form-control" type="text" name="date_end" value="{ROW.date_end}" id="time_end" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<em class="fa fa-calendar fa-fix"> </em>
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-24 col-md-12">
					<label class="control-label"><strong>{LANG.localtion_start}</strong> <span class="red">(*)</span></label> <select class="form-control" name="localtion_start">
						<option value="">---</option>
						<!-- BEGIN: select_localtion_start -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_localtion_start -->
					</select>
				</div>
				<div class="col-sm-24 col-md-12">
					<label class="control-label"><strong>{LANG.localtion_end}</strong> <span class="red">(*)</span></label> <select class="form-control" name="localtion_end">
						<option value="">---</option>
						<!-- BEGIN: select_localtion_end -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_localtion_end -->
					</select>
				</div>
			</div>
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.localtion_customer_start}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<select class="form-control" name="localtion_customer_start"> -->
			<!-- 				<option value=""> --- </option> -->
			<!-- 				BEGIN: select_localtion_customer_start -->
			<!-- 				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option> -->
			<!-- 				END: select_localtion_customer_start -->
			<!-- 			</select> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.localtion_customer_end}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<select class="form-control" name="localtion_customer_end"> -->
			<!-- 				<option value=""> --- </option> -->
			<!-- 				BEGIN: select_localtion_customer_end -->
			<!-- 				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option> -->
			<!-- 				END: select_localtion_customer_end -->
			<!-- 			</select> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.qty_customer}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<input class="form-control" type="text" name="qty_customer" value="{ROW.qty_customer}" pattern="^[0-9]*$"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" /> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.price_ticket}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<input class="form-control" type="text" name="price_ticket" value="{ROW.price_ticket}" /> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.localtion_cargo_start}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<select class="form-control" name="localtion_cargo_start"> -->
			<!-- 				<option value=""> --- </option> -->
			<!-- 				BEGIN: select_localtion_cargo_start -->
			<!-- 				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option> -->
			<!-- 				END: select_localtion_cargo_start -->
			<!-- 			</select> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.localtion_cargo_end}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<select class="form-control" name="localtion_cargo_end"> -->
			<!-- 				<option value=""> --- </option> -->
			<!-- 				BEGIN: select_localtion_cargo_end -->
			<!-- 				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option> -->
			<!-- 				END: select_localtion_cargo_end -->
			<!-- 			</select> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.qty_cargo}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<input class="form-control" type="text" name="qty_cargo" value="{ROW.qty_cargo}" pattern="^[0-9]*$"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" /> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.price_ship}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<input class="form-control" type="text" name="price_ship" value="{ROW.price_ship}" /> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.costs_title}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<input class="form-control" type="text" name="costs_title" value="{ROW.costs_title}" /> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.localtion_itinerary}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<select class="form-control" name="localtion_itinerary"> -->
			<!-- 				<option value=""> --- </option> -->
			<!-- 				BEGIN: select_localtion_itinerary -->
			<!-- 				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option> -->
			<!-- 				END: select_localtion_itinerary -->
			<!-- 			</select> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.costs_itinerary}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<input class="form-control" type="text" name="costs_itinerary" value="{ROW.costs_itinerary}" /> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<!-- 	<div class="form-group"> -->
			<!-- 		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.note}</strong></label> -->
			<!-- 		<div class="col-sm-19 col-md-20"> -->
			<!-- 			<textarea class="form-control" style="height:100px;" cols="75" rows="5" name="note">{ROW.note}</textarea> -->
			<!-- 		</div> -->
			<!-- 	</div> -->
			<div class="form-group text-center">
				<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
			</div>
		</div>
	</div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
	//<![CDATA[

	$('.timepicker').timepicker({
		timeFormat : 'HH:mm',
		interval : 30,
		minTime : '30',
		maxTime : '11:59pm',
		defaultTime : 'value',
		startTime : '07:00',
		dynamic : false,
		dropdown : true,
		scrollbar : true
	});

	$("#time_start,#time_end").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn : "focus",
		yearRange : "-90:+5",
	});

	//]]>
</script>
<!-- END: main -->