<!-- BEGIN: main -->

<script type="text/javascript">var de_del_cofirm = "{LANG.de_del_cofirm}";</script>
<script src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-vi.js" type="text/javascript"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
	
<form action="{FORM_ACTION}" method="get" class="well form-inline">
	<input type="hidden" name ='nv' value={MODULE_NAME}>
	<input type="hidden" name ='op' value={OP}>

	<div class="form-group pull-left">
		<input type="text" class="form-control" name="keywords" value="{SEARCH.keywords}" placeholder="{LANG.keywords}" />
	</div>

	<div class="input-group pull-left" style="margin-left: 10px">
		<input name="from" id="from" value="{SEARCH.from}" style="width: 100px;" class="form-control" maxlength="10" readonly="readonly" type="text"/>
		<span class="input-group-btn pull-left">
			<button class="btn btn-default" type="button" id="from-btn">
				<em class="fa fa-calendar fa-fix">&nbsp;</em>
			</button>
		</span>
	</div>

	<span class="text-middle pull-left">{LANG.to}</span>

	<span class="text-middle pull-left">{LANG.dateselect}</span>
	<div class="input-group pull-left" style="margin-left: 10px">
		<input name="to" id="to" value="{SEARCH.to}" style="width: 100px;" class="form-control" maxlength="10" readonly="readonly" type="text"/>
		<span class="input-group-btn pull-left">
			<button class="btn btn-default" type="button" id="to-btn">
				<em class="fa fa-calendar fa-fix">&nbsp;</em>
			</button>
		</span>
	</div>

	<select name="status" class="form-control w200 pull-left" style="margin-right: 20px">
		<option value="">{LANG.statusselect}</option>
		<!-- BEGIN: psopt3 -->
		<option value="{OPTION3.id}" {OPTION3.selected}>{OPTION3.name}</option>
		<!-- END: psopt3 -->
	</select>

	<input style="margin-right:50px" name="ok" type="submit" value="{LANG.search}" class="btn btn-success" />
</form>
<div class="table-responsive" style="margin-top: 10px">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{TABLE_CAPTION}
		</caption>
		<thead>
			<tr>
				<th>{LANG.sort}</th>
				<th>{LANG.title}</th>
				<th>{LANG.name_cus}</th>
				<th>{LANG.email_cus}</th>
				<th>{LANG.time_order}</th>
				<th>{LANG.status}</th>
				<th class="text-center">{LANG.feature}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {ROW.sort} </td>
				<td style="width:160px;"> {ROW.title} </td>
				<td style="width:140px;"> {ROW.cus_name} </td>

				<td> {ROW.cus_email} </td>
				<td> {ROW.addtime} </td>

				<td style="width:90px;">
				<select id='sta_{ROW.qid}' name='sta' onchange="nv_status('{ROW.qid}')" class="form-control w150">
					<!-- BEGIN: status -->
					<option value={STATUS.id} {STATUS.selected}>{STATUS.name}</option>
					<!-- END: status -->
				</select></td>
				<td class="text-center">
					<em class="fa fa-search fa-lg">&nbsp;</em><a href="{ROW.detail_url}">{LANG.detail}</a> - 
					<em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.edit_url}">{GLANG.edit}</a> - 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_de_del({ROW.qid});">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		<tbody>
			<!-- BEGIN: generate_page -->
			<tr class="footer">
				<td colspan="8"> {GENERATE_PAGE} </td>
			</tr>
			<!-- END: generate_page -->
	</table>
</div>

<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#to,#from").datepicker({
			showOn : "both",
			dateFormat : "dd.mm.yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImageOnly : true
		});

		$('#from-btn').click(function() {
			$("#from").datepicker('show');
		});

		$('#to-btn').click(function() {
			$("#to").datepicker('show');
		});
	});
	//]]>
</script>

<!-- END: main -->