<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mod_name={MOD_NAME}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col style="width: 300px;" />
				<col style="width: auto;" />
			</colgroup>
			<tfoot>
			<tr>
				<td class="text-center" colspan="2"><input name="submit" type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
			</tr>
			</tfoot>
			<tbody>
			<tr>
				<td><strong>{LANG.group_view_workforce}</strong></td>
				<td>
					<!-- BEGIN: group_view_workforce -->
					<div class="row">
						<label><input name="group_view_workforce[]" type="checkbox" value="{OPTION.value}" {OPTION.checked} />{OPTION.title}</label>
					</div>
					<!-- END: group_view_workforce -->
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.group_add_workforce}</strong></td>
				<td>
					<!-- BEGIN: group_add_workforce -->
					<div class="row">
						<label><input name="group_add_workforce[]" type="checkbox" value="{OPTION.value}" {OPTION.checked} />{OPTION.title}</label>
					</div>
					<!-- END: group_add_workforce -->
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.precode}</strong></td>
				<td><input class="form-control" type="text" value="{DATA.precode}" style="width: 100px;" name="precode" /><span class="text-middle"> {LANG.precode_note} </span></td>
			</tr>
		</table>
	</div>
</form>
<!-- END: main -->