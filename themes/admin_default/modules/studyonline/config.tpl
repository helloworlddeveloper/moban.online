<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->
<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_view}</caption>
			<tbody>
				<tr>
					<th>{LANG.setting_indexfile}</th>
					<td>
					<select class="form-control" name="indexfile">
						<!-- BEGIN: indexfile -->
						<option value="{INDEXFILE.key}"{INDEXFILE.selected}>{INDEXFILE.title}</option>
						<!-- END: indexfile -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.setting_homesite}</th>
					<td><input class= "form-control" type="text" value="{DATA.homewidth}" name="homewidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.homeheight}" name="homeheight" /></td>
				</tr>
				<tr>
					<th>{LANG.setting_thumbblock}</th>
					<td><input class= "form-control" type="text" value="{DATA.blockwidth}" name="blockwidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.blockheight}" name="blockheight" /></td>
				</tr>
				<tr>
					<th>{LANG.setting_imagefull}</th>
					<td><input class= "form-control" type="text" value="{DATA.imagefull}" name="imagefull" /></td>
				</tr>
                <tr>
					<th>{LANG.setting_auto_tags}</th>
					<td><input type="checkbox" value="1" name="auto_tags"{AUTO_TAGS}/></td>
				</tr>
				<tr>
					<th>{LANG.setting_per_page}</th>
					<td>
					<select class="form-control" name="per_page">
						<!-- BEGIN: per_page -->
						<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
						<!-- END: per_page -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.setting_st_links}</th>
					<td>
					<select class="form-control" name="st_links">
						<!-- BEGIN: st_links -->
						<option value="{ST_LINKS.key}"{ST_LINKS.selected}>{ST_LINKS.title}</option>
						<!-- END: st_links -->
					</select></td>
				</tr>
                <tr>
					<th>{LANG.numview_guest}</th>
					<td><input class= "form-control" type="text" value="{DATA.numview_guest}" name="numview_guest"/></td>
				</tr>
				<tr>
					<th>{LANG.showhometext}</th>
					<td><input type="checkbox" value="1" name="showhometext"{SHOWHOMETEXT}/></td>
				</tr>
				<tr>
					<th>{LANG.socialbutton}</th>
					<td><input type="checkbox" value="1" name="socialbutton"{SOCIALBUTTON}/></td>
				</tr>
				<tr>
					<th>{LANG.allowed_rating_point}</th>
					<td>
						<select class="form-control" name="allowed_rating_point">
							<!-- BEGIN: allowed_rating_point -->
							<option value="{RATING_POINT.key}"{RATING_POINT.selected}>{RATING_POINT.title}</option>
							<!-- END: allowed_rating_point -->
						</select>
					</td>
				</tr>
                <tr>
					<th>{LANG.show_no_image}</th>
					<td><input class="form-control" name="show_no_image" id="show_no_image" value="{SHOW_NO_IMAGE}" style="width:340px;" type="text"/> <input id="select-img-setting" value="{GLANG.browse_image}" name="selectimg" type="button" class="btn btn-info"/></td>
				</tr>
                <tr>
					<th>{LANG.facebookAppID}</th>
					<td><input class="form-control w150" name="facebookappid" value="{DATA.facebookappid}" type="text"/><span class="text-middle">{LANG.facebookAppIDNote}</span></td>
				</tr>
                <tr>
					<th>{LANG.setting_alias_lower}</th>
					<td><input type="checkbox" value="1" name="alias_lower"{ALIAS_LOWER}/></td>
				</tr>
                <tr>
					<th colspan="2" style="color:#f00">{LANG.setting_videostreaming}</th>
				</tr>
                <tr>
					<th>{LANG.videostreaming_option}</th>
					<td>
                        <select class="form-control" name="streaming">
    						<!-- BEGIN: streaming -->
    						<option value="{STREAMING.key}"{STREAMING.selected}>{STREAMING.title}</option>
    						<!-- END: streaming -->
    					</select>
                    </td>
				</tr>
                <tr>
					<th>{LANG.server_streaming}</th>
					<td><input class="form-control" type="text" value="{DATA.server_streaming}" name="server_streaming"/></td>
				</tr>
			</tbody>
            
            <tfoot>
				<tr>
					<td class="text-center" colspan="2">
						<input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit" />
						<input type="hidden" value="1" name="savesetting" />
					</td>
				</tr>
			</tfoot>
		</table>
        
	</div>
</form>
<script type="text/javascript">
//<![CDATA[
var CFG = [];
CFG.path = '{PATH}';
CFG.currentpath = '{CURRENTPATH}';
$("#select-img-setting").click(function() {
	var area = "show_no_image";
	var type = "image";
	var path = CFG.path;
	var currentpath = CFG.currentpath;
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
//]]>
</script>
<!-- END: main -->