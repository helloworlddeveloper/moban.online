<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<input class="form-control" type="text" name="namesearch" value="{val_namesearch}" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<select class="form-control" name="transaction">
						<option value="all">---{LANG.typetransaction_select}---</option>
						<!-- BEGIN: looptransaction -->
						<option {sl_transaction} value="{key_transaction}">{val_transaction}</option>
						<!-- END: looptransaction -->
					</select>
				</div>
			</div>
			<div class="col-xs-24 col-md-3">
				<div class="form-group">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control datepicker" value="{starttime}" type="text" name="starttime" readonly="readonly" placeholder="{LANG.starttime}" /> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-24 col-md-3">
				<div class="form-group">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control datepicker" value="{endtime}" type="text" name="endtime" readonly="readonly" placeholder="{LANG.endtime}" /> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-24 col-md-3">
				<div class="form-group">
					<div class="form-group">
						<select name="per_page" class="form-control">
							<option value="0">---{LANG.per_page}---</option>
							<!-- BEGIN: s_per_page -->
							<option value="{SEARCH_PER_PAGE.page}"{SEARCH_PER_PAGE.selected} >{SEARCH_PER_PAGE.page}</option>
							<!-- END: s_per_page -->
						</select>
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
<span class="pull-right m-bottom"><strong>{LANG.total}: {sum}</strong></span>
<div class="clearfix"></div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="w50 text-center">{LANG.stt}</th>
				<th class="w100">{LANG.codetransaction}</th>
				<th>{LANG.usertransaction}</th>
				<th class="w150">{LANG.moneytransaction}</th>
				<th class="w150 text-center">{LANG.datetransaction}</th>
                <th>{LANG.transaction_data}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{stt}</td>
				<td>{CONTENT.code}</td>
				<td>{CONTENT.userid}</td>
				<td align="right">{CONTENT.status}{CONTENT.money_total}</td>
				<td class="text-center">{CONTENT.createdtime}</td>
                <td>{CONTENT.transaction_data}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- BEGIN: page -->
<div class="text-center">{PAGE}</div>
<!-- END: page -->

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