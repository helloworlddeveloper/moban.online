<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col span="2" />
				<col class="w100" />
				<col class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th>{LANG.alias}</th>
					<th>{LANG.keywords}</th>
					<th class="text-center">{LANG.numsearch}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.tid}" name="idcheck[]" /></td>
					<td>{ROW.alias}</td>
					<td>
						{ROW.keywords}
						<!-- BEGIN: incomplete -->
					 	<em class="text-danger fa fa-lg fa-warning tags-tip" data-toggle="tooltip" data-placement="top" title="{LANG.tags_no_description}">&nbsp;</em>
						<!-- END: incomplete -->
					</td>
					<td class="text-center">{ROW.numsearch}</td>
					<td class="text-center">
						<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
						<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_tags({ROW.tid})">{GLANG.delete}</a>
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><input class="btn btn-danger" name="submit_dell" type="button" onclick="nv_del_check_tags(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{GLANG.delete}" /></td>
					<td colspan="3">
						<!-- BEGIN: other -->
						<strong>{LANG.alias_search}</strong>
						<!-- END: other -->
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<script type="text/javascript">
$(function(){
	$('.tags-tip').tooltip();
});
function nv_del_tags(tid) {
	if (confirm(nv_is_del_confirm[0])) {
		$("#module_show_list").html('<p class="text-center"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></p>').load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=main&del_tid=" + tid + "&num=" + nv_randomPassword(10));
	}
	return false;
}

function nv_search_tag(tid) {
	$("#module_show_list").html('<p class="text-center"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></p>').load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=main&q=" + rawurlencode($("#q").val()) + "&num=" + nv_randomPassword(10));
	return false;
}
function nv_del_check_tags(oForm, checkss, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}

	if (listid != '') {
		if (confirm(nv_is_del_confirm[0])) {
			$("#module_show_list").html('<p class="text-center"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></p>').load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=main&q=" + rawurlencode($("#q").val()) + "&del_listid=" + listid + "&checkss=" + checkss+"&num=" + nv_randomPassword(10));
		}
	} else {
		alert(msgnocheck);
	}
	return false;
}
</script>
<!-- END: main -->