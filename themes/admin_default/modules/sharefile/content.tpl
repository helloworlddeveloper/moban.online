<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td class="w250"> {LANG.file_title} </td>
							<td><input class="w300 form-control" type="text" value="{DATA.title}" name="title" id="title"/></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td class="w250" style="vertical-align:top"> {LANG.file_myfile} </td>
					<td>
						<div id="fileupload_items">
								<input class="w300 form-control pull-left" type="text" value="{DATA.fileupload}" name="fileupload" id="fileupload" maxlength="255" />
								&nbsp; <input class="btn btn-info" type="button" value="{LANG.file_selectfile}" name="selectfile" onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload&path={FILES_DIR}&type=file', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' );return false;" />
						</div>
					</td>
				</tr>
				<tr>
					<td class="w250"> {LANG.link_file} </td>
					<td>
						<div>
							<input class="w300 form-control pull-left" type="text" value="{DATA.link_file}" name="link_file" id="link_file" maxlength="255" />
						</div>
					</td>
				</tr>
				<tr>
					<td> {LANG.file_size} </td>
					<td><input type="text" class="w100 form-control pull-left" value="{DATA.filesize}" name="filesize" id="filesize" maxlength="11" /><span class="text-middle"> {LANG.config_maxfilemb} </span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align:center;padding-top:15px">
		<input type="submit" name="submit" value="{LANG.confirm}" class="btn btn-primary" />
	</div>
</form>
<!-- END: main -->