<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<label><strong>{LANG.title}</strong> <span class="red">(*)</span></label> <input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
			</div>
			<div class="form-group">
				<label><strong>{LANG.image}</strong></label>
				<div class="input-group">
					<input class="form-control" type="text" name="image" value="{ROW.image}" id="id_image" /> <span class="input-group-btn">
						<button class="btn btn-default selectfile" type="button">
							<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
						</button>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label><strong>{LANG.html}</strong> <span class="red">(*)</span></label> {ROW.html} <span style="margin-top: 10px; display: block; font-weight: bold">{LANG.content_note}</span>
				<blockquote class="personal">
					<div class="row">
						<!-- BEGIN: personal -->
						<div class="col-xs-24 col-sm-12">
							<label>{PERSONAL.index}</label> {PERSONAL.value}
						</div>
						<!-- END: personal -->
					</div>
				</blockquote>
			</div>
		</div>
	</div>
	<div class="form-group text-center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	$(".selectfile").click(function() {
		var area = "id_image";
		var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable
				+ "=upload&popup=1&area=" + area + "&path="
				+ path + "&type=" + type + "&currentpath="
				+ currentpath, "NVImg", 850, 420,
				"resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	//]]>
</script>
<!-- END: main -->