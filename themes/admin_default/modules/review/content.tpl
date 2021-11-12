<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<input type="hidden" value="{serviceid}" name="serviceid" />
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col class="w200" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<td class="text-right"> {LANG.title} <sup class="required">(*)</sup></td>
						<td><input class="w300 form-control pull-left" type="text" value="{DATA.title}" name="title" maxlength="250" /></td>
					</tr>
					<tr>
						<td class="text-right"> {LANG.description} <sup class="required">(*)</sup></td>
						<td><textarea name="description" class="form-control" style="width: 100%">{DATA.description}</textarea></td>
					</tr>
					<tr>
						<td class="text-right">{LANG.image}</td>
						<td>
							<div id="otherimage">
								<!-- BEGIN: otherfile -->
								<div class="form-group">
									<div class="input-group">
										<input value="{DATA_FILE.value}" name="file[]" id="file_{DATA_FILE.id}" class="form-control" maxlength="255">
										<span class="input-group-btn">
											<button class="btn btn-default" type="button" onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=file_{DATA_FILE.id}&path={NV_UPLOADS_DIR}/{MODULE_UPLOAD}&currentpath={CURRENT}&type=file', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; ">
												<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
											</button>
										</span>
									</div>
								</div>
								<!-- END: otherfile -->
							</div>
							<input type="button" class="btn btn-info" onclick="nv_add_otherimage();" value="{LANG.add_otherfile}">
					</tr>
					<tr>
						<td class="text-right"> {LANG.timeuse} <sup class="required">(*)</sup></td>
						<td><input class="w300 form-control pull-left" type="text" value="{DATA.timeuse}" id="timeuse" name="timeuse" maxlength="10" /></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row text-center"><input type="submit" value="{LANG.save}" class="btn btn-primary"/>
	</div>
</form>
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
    var file_items = '{FILE_ITEMS}';
    var file_selectfile = '{LANG.file_selectfile}';
    var nv_base_adminurl = '{NV_BASE_ADMINURL}';
    var inputnumber = '{LANG.error_inputnumber}';
    var file_dir = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
    var currentpath = "{CURRENT}";
    var uploads_dir_user = '{UPLOADS_DIR_USER}';
    $("#timeuse").datepicker({
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        showOn : 'focus'
    });
</script>
<!-- END: main -->