<!-- BEGIN: main -->
<div id="content">
	<!-- BEGIN: success -->
		<div class="alert alert-success">
			<i class="fa fa-check-circle"></i> {SUCCESS}
		</div>
	<!-- END: success -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANG.setting}</h3>
            <div class="pull-right">
                <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary" title="{LANG.save}"><i class="fa fa-save"></i>
                </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
            </div>
            <div style="clear:both"></div>
        </div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
				<input type="hidden" value="1" name="savesetting" />				
				<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
				<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
				<div class="form-group">
					<label class="col-sm-6 control-label" style="padding-top: 0px;">{LANG.setting_home_view}:</label>
					<div class="col-sm-18">
						<select class="form-control" name="home_view">
							<!-- BEGIN: home_view -->
							<option value="{HOME_VIEW.key}" {HOME_VIEW.selected}>{HOME_VIEW.title}</option>
							<!-- END: home_view -->
						</select>	
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label" style="padding-top: 0px;">{LANG.setting_album_view}:</label>
					<div class="col-sm-18">
						<select class="form-control" name="album_view">
							<!-- BEGIN: album_view -->
							<option value="{ALBUM_VIEW.key}" {ALBUM_VIEW.selected}>{ALBUM_VIEW.title}</option>
							<!-- END: album_view -->
						</select>	
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label">{LANG.setting_per_page_album}:</label>
					<div class="col-sm-18">
						<select class="form-control" name="per_page_album">
							<!-- BEGIN: per_page_album -->
							<option value="{PER_PAGE_ALBUM.key}" {PER_PAGE_ALBUM.selected}>{PER_PAGE_ALBUM.title}</option>
							<!-- END: per_page_album -->
						</select>	
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label">{LANG.setting_per_page_photo}:</label>
					<div class="col-sm-18">
						<select class="form-control" name="per_page_photo">
							<!-- BEGIN: per_page_photo -->
							<option value="{PER_PAGE_PHOTO.key}" {PER_PAGE_PHOTO.selected}>{PER_PAGE_PHOTO.title}</option>
							<!-- END: per_page_photo -->
						</select>		
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/footer.js"></script>
<script type="text/javascript">
//<![CDATA[
$('button[type=\'submit\']').on('click', function() {
	$("form[id*='form-']").submit();
});

$("input[name=selectimg]").click(function(){
	var area = "module_logo";
	var type= "image";
	var path= "{PATH}";
	var currentpath= "{CURRENTPATH}";
	nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
//]]>
</script>

<!-- END: main -->