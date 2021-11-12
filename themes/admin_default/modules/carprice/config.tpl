<!-- BEGIN: main -->
<form action="" method="post" class="form-horizontal">
	<div class="panel panel-default">
		<div class="panel-heading">{LANG.config_system}</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-4 control-label"><strong>{LANG.road_use}</strong></label>
				<div class="col-sm-20">
					<input type="text" name="road_use" onkeyup="this.value=FormatNumber(this.value);" value="{DATA.road_use}" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><strong>{LANG.registration}</strong></label>
				<div class="col-sm-20">
					<input type="text" name="registration" onkeyup="this.value=FormatNumber(this.value);" value="{DATA.registration}" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><strong>{LANG.civil_insurance}</strong></label>
				<div class="col-sm-20">
					<div class="form-group">
						<label class="col-sm-2 control-label"><strong>{LANG.civil_insurance_4}</strong></label>
						<div class="col-sm-22">
							<input type="text" name="civil_insurance_4" onkeyup="this.value=FormatNumber(this.value);" value="{DATA.civil_insurance_4}" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><strong>{LANG.civil_insurance_5}</strong></label>
						<div class="col-sm-22">
							<input type="text" name="civil_insurance_5" onkeyup="this.value=FormatNumber(this.value);" value="{DATA.civil_insurance_5}" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><strong>{LANG.civil_insurance_6}</strong></label>
						<div class="col-sm-22">
							<input type="text" name="civil_insurance_6" onkeyup="this.value=FormatNumber(this.value);" value="{DATA.civil_insurance_6}" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><strong>{LANG.civil_insurance_7}</strong></label>
						<div class="col-sm-22">
							<input type="text" name="civil_insurance_7" onkeyup="this.value=FormatNumber(this.value);" value="{DATA.civil_insurance_7}" class="form-control" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
	</div>
</form>
<script>
	$(".selectfile").click(function() {
		var area = "id_image";
		var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
    $(".selectid_no_image_cat").click(function() {
		var area = "id_no_image_cat";
		var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- BEGIN: main -->