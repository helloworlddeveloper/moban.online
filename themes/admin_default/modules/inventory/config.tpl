<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mod_name={MOD_NAME}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col style="width: 300px;" />
				<col style="width: auto;" />
			</colgroup>
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
		</table>
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.setting_export_excel}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
			<tr>
				<td><strong>{LANG.setting_export_headerfile}</strong></td>
				<td>
					<div class="form-group">
						<div class="input-group">
							<input class="form-control" type="text" name="headerfile" id="headerfile" value="{DATA.headerfile}"/>
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="selectheaderfile">
									<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_export_companyname}</strong></td>
				<td>
					<input class="form-control" name="companyname" value="{DATA.companyname}" />
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_export_address}</strong></td>
				<td>
					<input class="form-control" name="address" value="{DATA.address}" />
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_export_leader_name}</strong></td>
				<td>
					<input class="form-control" name="leader_name" value="{DATA.leader_name}" />
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_export_accountant_name}</strong></td>
				<td>
					<input class="form-control" name="accountant_name" value="{DATA.accountant_name}" />
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_export_creators_name}</strong></td>
				<td>
					<input class="form-control" name="creators_name" value="{DATA.creators_name}" />
				</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td class="text-center" colspan="2"><input name="submit" type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
			</tr>
			</tfoot>
		</table>
	</div>
</form>
<script type="text/javascript">
    $('#download_active').change(function(){
        $('#download_groups').toggle();
    });
    var path = "{NV_UPLOADS_DIR}/{module_name}";
    var currentpath = path;
    var type = "image";
    $("#selectheaderfile").click(function() {
        var area = "headerfile";
        nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
</script>

<!-- END: main -->