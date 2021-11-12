<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
    <input type="hidden" name="khoahocid" value="{ROW.khoahocid}" />
	<div class="table-responsive">
	   <div class="col-sm-24 col-md-18">
            <table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td> <strong>{LANG.title_baihoc}</strong> <span style="color:#f00">(*)</span> </td>
    					<td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
                     </tr>
                    <tr>
    					<td><strong>{LANG.image_baihoc}</strong> <span style="color:#f00">(*)</span></td>
    					<td><input style="width:60%;float:left" class="form-control" type="text" name="image" value="{ROW.image}" id="id_images" />&nbsp;<button type="button" class="btn btn-info" id="img_images"><i class="fa fa-folder-open-o">&nbsp;</i>Browse</button></td>
    				</tr>
                    <tr>
    					<td><strong>{LANG.fileaddtack}</strong></td>
    					<td><input style="width:60%;float:left" class="form-control" type="text" name="fileaddtack" value="{ROW.fileaddtack}" id="fileaddtack" />&nbsp;<button type="button" class="btn btn-info" id="btn_fileaddtack"><i class="fa fa-folder-open-o">&nbsp;</i>Browse</button></td>
    				</tr>
    				<tr>
    					<td colspan="2"><strong>{LANG.description}</strong> <span style="color:#f00">(*)</span></td>
    				</tr>
                    <tr>
                        <td colspan="2"><textarea class="form-control" style="width: 98%; height:100px;" cols="75" rows="5" name="description">{ROW.description}</textarea></td>
                    </tr>
    			</tbody>
    		</table>
            <table class="table table-striped table-bordered table-hover" id="item_video">
                <caption>{LANG.list_video_baihoc}</caption>
                <thead>
                    <tr>
                        <th>{LANG.video_title}</th>
                        <th>{LANG.video_path}</th>
                    </tr>
                </thead>
    			<tbody>
                    <!-- BEGIN: itemvideo -->
                    <tr>
    					<td><input type="text" class="form-control" name="video_title[]" value="{ITEM.video_title}" /></td>
    					<td><input style="width:60%;float:left" class="form-control" type="text" name="video_path[]" value="{ITEM.video_path}" id="video_path_{ITEM.stt}" />&nbsp;<button type="button" class="btn btn-info" onclick="get_path_video( 'video_path_{ITEM.stt}' )"><i class="fa fa-folder-open-o">&nbsp;</i>Browse</button></td>
    				</tr>
                    <!-- END: itemvideo -->
    			</tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><input type="button" value="{LANG.add_morevideo}" onclick="nv_studyonline_add_video_item('{LANG.voting_question_num}');" class="btn btn-info" /></td>
                    </tr>
                </tfoot>
    		</table>
        </div>
        <div class="col-sm-24 col-md-6">
            <table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td><strong>{LANG.titleseo}</strong>: <input placeholder="{LANG.titleseo}" class="form-control" type="text" name="titleseo" value="{ROW.titleseo}" /></td>
                    </tr>
                     <tr>
    					<td><strong>{LANG.alias}</strong>: <input placeholder="{LANG.alias}"  id="id_alias"class="form-control" type="text" name="alias" value="{ROW.alias}" /></td>
    				</tr>
                    <tr>
    					<td><strong>{LANG.numviewtime}</strong>: <input placeholder="{LANG.numviewtime}" class="form-control" type="text" name="numviewtime" value="{ROW.numviewtime}" /></td>
                    </tr>
                    <tr>
    					<td><strong>{LANG.price_baihoc}</strong>: <input placeholder="{LANG.price_baihoc}" class="form-control" type="text" name="price" value="{ROW.price}" /></td>
                    </tr>
                    <tr>
    					<td>
                            <strong style="float:left">{LANG.timephathanh}</strong>:<br />
                            <select style="width:80px;float:left" class="form-control" name="ehour">
								{ehour}
							</select>
							<select style="width:80px;float:left" class="form-control" name="emin">
								{emin}
							</select>
                            <input style="width:100px;float:left" placeholder="{LANG.timephathanh}" class="form-control" type="text" name="timephathanh" value="{ROW.timephathanh}" id="timephathanh" />
                        </td>
                    </tr>
                    <tr>
    					<td><strong>{LANG.timeamount} ({LANG.minute})</strong>: <input placeholder="{LANG.timeamount} ({LANG.minute})" class="form-control" type="text" name="timeamount" value="{ROW.timeamount}" id="timeamount" /></td>
                    </tr>
                    <tr>
    					<td>
                            <select class="form-control" name="status">
            					<!-- BEGIN: select_status -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: select_status -->
            				</select>
                        </td>
    				</tr>
                </tbody>
            </table>    
         </div>    
	</div>
	<div style="text-align: center">
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    var items='{NEW_ITEM_NUM}';
    $("#timephathanh").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "images/calendar.gif",
		buttonImageOnly : true
	});
    var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
    var currentpath = '{currentpath}';
    $("#img_images").click(function() {
		var area = "id_images";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
    $("#btn_fileaddtack").click(function() {
		var area = "fileaddtack";
		var type = "file";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
    
    function get_path_video( area ){
		var type = "file";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
    }
    function nv_get_alias(id) {
		var title = strip_tags( $("input[name='titleseo']").val() );
		if (title != '') {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
				$("#"+id).val( strip_tags( res ) );
			});
		}
		return false;
	}
    <!-- BEGIN: auto_get_alias -->
    	$("input[name='titleseo']").change(function() {
    		nv_get_alias('id_alias');
    	});
    <!-- END: auto_get_alias -->
</script>
<!-- END: main -->