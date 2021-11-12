<!-- BEGIN: main -->

<script type="text/javascript">var de_del_cofirm = "{LANG.de_del_cofirm}";</script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
	
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

	<input style="margin-right:50px" name="ok" type="submit" value="{LANG.search}" class="btn btn-success" />
</form>

<form name="list_post" id="list_post" action="{action}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>{TABLE_CAPTION}</caption>
			<colgroup>
				<col span="2" class="w50" />
				<col />
				<col span="4" class="w200" />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="text-center"> {LANG.sort} </th>
					<th> {LANG.title} </th>
					<th> {LANG.name_cus} </th>
					<th> {LANG.email_cus} </th>
					<th> {LANG.time_order} </th>
					<th class="text-center"> {LANG.feature} </th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox"
					onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"
					value="{ROW.qid}" name="idcheck[]" id ="idcheck[]"></td>
					<td class="text-center"> {ROW.sort} </td>
					<td> {ROW.title} </td>
					<td> {ROW.cus_name} </td>
					<td> {ROW.cus_email} </td>
					<td> {ROW.addtime} </td>
					<td class="text-center"><em class="fa fa-search fa-lg">&nbsp;</em><a href="{ROW.detail_url}">{LANG.detail}</a> - <em class="fa fa-edit fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_de_del({ROW.qid});">{GLANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			<tbody>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr class="footer">
					<td colspan="7"> {GENERATE_PAGE} </td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
		</table>
	</div>
	<table>
		<tbody>
			<tr>
				<td>
				<select id="action" class="form-control w100 pull-left">
					<!-- BEGIN: action -->
					<option value="{key_action}">{value_action}</option>
					<!-- END: action -->
				</select><input type = "button" onclick = "doaction('{OP}');" value ="OK" class="btn btn-warning" style="margin-left: 10px"></td>
				<td align="right">{page}</td>
			</tr>
		</tbody>
	</table>
	
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var doempty = "{LANG.doempty}";
		$(document).ready(function() {
			$("#from,#to").datepicker({
				showOn : "both",
				dateFormat : "dd.mm.yy",
				changeMonth : true,
				changeYear : true,
				showOtherMonths : true,
				buttonImageOnly : true
			});
			
			$('#from-btn').click(function(){
				$("#from").datepicker('show');
			});
			
			$('#to-btn').click(function(){
				$("#to").datepicker('show');
			});
		});
		//]]>
	</script>
</form>
<!-- END: main -->