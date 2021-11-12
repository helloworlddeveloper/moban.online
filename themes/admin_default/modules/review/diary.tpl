<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<input type="hidden" name="serviceid" value="{DATA_SERVICE.id}" />
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" maxlength="64" name="q" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<select class="form-control" name="sstatus">
						<option value="-1"> -- {LANG.search_status} -- </option>
						<!-- BEGIN: search_status -->
						<option value="{SEARCH_STATUS.key}" {SEARCH_STATUS.selected} >{SEARCH_STATUS.value}</option>
						<!-- END: search_status -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
                    <a class="btn btn-primary" href="{addproduct}">{LANG.addproduct}</a>
				</div>
			</div>
		</div>
		<input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
		<label><em>{LANG.search_note}</em></label>
	</form>
</div>

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="text-center">{LANG.title}</th>
					<th class="text-center">{LANG.description}</th>
					<th class="text-center">{LANG.timeuse}</th>
					<th class="text-center">{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr class="{ROW.class}">
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
					<td>{ROW.title}</td>
					<td class="text-left">{ROW.description}</td>
					<td>{ROW.timeuse}</td>
					<td title="{ROW.status}">{ROW.status}</td>
					<td class="text-center">
						<i class="fa fa-edit fa-lg">&nbsp;</i><a href="{ROW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{ROW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr class="text-left">
					<td colspan="12">
					<select class="form-control" name="action" id="action">
						<!-- BEGIN: action -->
						<option value="{ACTION.value}">{ACTION.title}</option>
						<!-- END: action -->
					</select><input type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{LANG.action}" /></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#catid").select2({
			language : '{NV_LANG_DATA}'
		});
	});
</script>
<!-- END: main -->