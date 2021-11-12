<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
	   <div class="col-sm-24 col-md-18">
            <table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td> <strong>{LANG.title_khoahoc}</strong> <span style="color:#f00">(*)</span> </td>
    					<td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
                     </tr>
                    <tr>
    					<td><strong>{LANG.images}</strong> <span style="color:#f00">(*)</span></td>
    					<td><input style="width:60%;float:left" class="form-control" type="text" name="image" value="{ROW.image}" id="id_images" />&nbsp;<button type="button" class="btn btn-info" id="img_images"><i class="fa fa-folder-open-o">&nbsp;</i>Browse</button></td>
    				</tr>
					<tr>
						<td><strong>{LANG.requirewatch}</strong> <span style="color:#f00">(*)</span></td>
						<td><input style="width:60%;float:left" class="form-control" type="text" name="requirewatch" value="{ROW.requirewatch}"  /></td>
					</tr>
    				<tr>
    					<td colspan="2"><strong>{LANG.description}</strong> <span style="color:#f00">(*)</span></td>
    				</tr>
                    <tr>
                        <td colspan="2"><textarea class="form-control" style="width: 98%; height:100px;" cols="75" rows="5" name="description">{ROW.description}</textarea></td>
                    </tr>
    				<tr>
    					<td colspan="2"><strong>{LANG.hometext}</td>
    				</tr>
                    <tr>
    					<td colspan="2">{ROW.hometext}</td>
    				</tr>
                     
    			</tbody>
    		</table>
        </div>
        <div class="col-sm-24 col-md-6">
            <table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td><input placeholder="{LANG.titleseo}" class="form-control" type="text" name="titleseo" value="{ROW.titleseo}" /></td>
                    </tr>
                     <tr>
    					<td><input placeholder="{LANG.alias}"  id="id_alias"class="form-control" type="text" name="alias" value="{ROW.alias}" /></td>
    				</tr>
                    <tr>
                        <td>
                            <!-- BEGIN:block_cat -->
							<p class="message_head">
								<cite>{LANG.content_block}:</cite>
							</p>
							<div class="message_body" style="overflow: auto">
								<!-- BEGIN: loop -->
								<div class="row">
									<label><input type="checkbox" value="{BLOCKS.bid}" name="bids[]" {BLOCKS.checked}>{BLOCKS.title}</label>
								</div>
								<!-- END: loop -->
							</div>
    						<!-- END:block_cat -->
                        </td>
                    </tr>
                    <tr>
    					<td>{LANG.isvip}&nbsp;<input class="form-control" type="checkbox" name="isvip" value="1"{ROW.ckisvip} id="isvip" /></td>
                    </tr>
                    <tr>
    					<td>{LANG.isfreetrial}&nbsp;<input class="form-control" type="checkbox" name="isfreetrial" value="1"{ROW.ckisfreetrial} id="isfreetrial" /></td>
                    </tr>
                    <tr>
    					<td>{LANG.numlession}<br /><input placeholder="{LANG.numlession}" class="form-control" type="text" name="numlession" value="{ROW.numlession}" /></td>
                    </tr>
                    <tr>
    					<td>{LANG.numviewtime}<br /><input placeholder="{LANG.numviewtime}" class="form-control" type="text" name="numviewtime" value="{ROW.numviewtime}" /></td>
                    </tr>
                    <tr>
    					<td>{LANG.price}<br /><input placeholder="{LANG.price}" class="form-control" type="text" name="price" value="{ROW.price}" /></td>
                    </tr>
                    <tr>
    					<td>{LANG.timestudy}<br /><input placeholder="{LANG.timestudy}" class="form-control" type="text" name="timestudy" value="{ROW.timestudy}" id="timestudy" /></td>
                    </tr>
                    <tr>
    					<td>{LANG.timeend}<br /><input placeholder="{LANG.timeend}" class="form-control" type="text" name="timeend" value="{ROW.timeend}" id="timeend" /></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="message_head">
								<cite>{LANG.content_tag}:</cite>
							</p>
							<div class="message_body" style="overflow: auto">
								<div class="clearfix uiTokenizer uiInlineTokenizer">
									<div id="keywords" class="tokenarea">
										<!-- BEGIN: keywords -->
										<span class="uiToken removable" title="{KEYWORDS}" ondblclick="$(this).remove();"> {KEYWORDS} <input type="hidden" autocomplete="off" name="keywords[]" value="{KEYWORDS}" /> <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a> </span>
										<!-- END: keywords -->
									</div>
									<div class="uiTypeahead">
										<div class="wrap">
											<input type="hidden" class="hiddenInput" autocomplete="off" value="" />
											<div class="innerWrap">
												<input id="keywords-search" type="text" placeholder="{LANG.input_keyword_tags}" class="form-control textInput" style="width: 100%;" />
											</div>
										</div>
									</div>
								</div>
							</div>
                        </td>
                    </tr>
                     <tr>   
    					<td>
                            <select class="form-control" name="subjectid">
                                <option value="0">--{LANG.subject_of_khoahoc}--</option>
            					<!-- BEGIN: classes_subject -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: classes_subject -->
            				</select>
                        </td>
    				</tr>
    				<tr>
    					<td>
                            <div class="message_body" style="overflow: auto">
								<div class="clearfix uiTokenizer uiInlineTokenizer">
		                            <div id="teacherid" class="tokenarea">
		                                <!-- BEGIN: classes_teacher -->
		                                <span class="uiToken removable" title="{TEACHER.title} - {TEACHER.mobile}" ondblclick="$(this).remove();">
		                                    {TEACHER.title} - {TEACHER.mobile}
		                                    <input type="hidden" autocomplete="off" name="teacherid[]" value="{TEACHER.id}" />
		                                    <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a>
		                                </span>
		                                <!-- END: classes_teacher -->
		                            </div>
		                            <div class="uiTypeahead">
		                                <div class="wrap">
		                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
		                                    <div class="innerWrap">
		                                        <input id="teacher_search" type="text" placeholder="{LANG.teacher_of_khoahoc}" class="form-control textInput" style="width: 100%;" />
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                	</div>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <select id="class_box" class="form-control" name="classid">
                                <option value="0">--{LANG.class_of_khoahoc}--</option>
            					<!-- BEGIN: classes_class -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: classes_class -->
            				</select>
                        </td>
    				</tr>
                    <tr> 
        				<td>
                            <strong>{LANG.listtag}</strong><br />
                            <!-- BEGIN: listtag -->
            				<input class="form-control" type="checkbox" name="listtag[]"{TAG.ck} id="tag_{TAG.tag_id}" value="{TAG.tag_id}" /><label for="tag_{TAG.tag_id}">{TAG.tag_name}</label>
           					<!-- END: listtag -->
                        </td>
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
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/studyonline_searchajax.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $("#timestudy,#timeend").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "images/calendar.gif",
		buttonImageOnly : true
	});
    $("#img_images").click(function() {
		var area = "id_images";
		var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_NAME}/khoahoc";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
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