<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<div id="content">
    <!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <br>
    </div>
    <!-- END: error_warning -->
    <div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
			<div class="pull-right">
				<button type="submit" data-toggle="tooltip" class="btn btn-primary" title="{LANG.save}"><i class="fa fa-save"></i></button> 
				<a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-album" class="form-horizontal">
				<ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-general" data-toggle="tab">{LANG.general}</a> </li>
				    <li class="disabled"><a href="#tab-image" rel="tab-image" data-toggle="tab">{LANG.album_image}</a></li>
                    <li class="disabled"><a href="#tab-info-image" rel="tab-info-image" data-toggle="tab">{LANG.album_info_image}</a></li>
                </ul>
				<div class="tab-content">
                     <div class="tab-pane active" id="tab-general">
						<ul class="glt-upload-step clearfix">
								<li class="active">
									<span class="stepicon"><span>&nbsp;</span></span>
									<span class="steptext">Bước 1: Tạo Album</span>
								</li>
								<li>
									<span class="stepicon"><span>&nbsp;</span></span>
									<span class="steptext">Bước 2: Chọn và tải ảnh</span>
								</li>
								<li>
									<span class="stepicon"><span>&nbsp;</span></span>
									<span class="steptext">Bước 3: Cập nhật thông tin ảnh</span>
								</li>
						</ul>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-name">{LANG.album_name}</label>
							<div class="col-sm-20">
								<input type="text" name="name" value="{DATA.name}" placeholder="{LANG.album_name}" id="input-name" class="form-control" />
								<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-alias">{LANG.album_alias}</label>
							<div class="col-sm-20">
								<div class="input-group">
									<input class="form-control" name="alias" placeholder="{LANG.album_alias}"  type="text" value="{DATA.alias}" maxlength="255" id="input-alias"/>
									<div class="input-group-addon fixaddon" data-toggle="tooltip" title="{LANG.create_alias}">
										&nbsp;<em class="fa fa-refresh fa-lg fa-pointer text-middle" onclick="get_alias();">&nbsp;</em>
									</div>
								</div>
							</div>
						</div>	
						<div class="form-group required">
							<label class="col-sm-4 control-label" for="input-parent">{LANG.album_category}</label>
							<div class="col-sm-20">
								<select class="form-control" name="category_id">
									<option value="0">{LANG.album_category_select}</option>
									<!-- BEGIN: category -->
									<option value="{CATALOG.key}" {CATALOG.selected}>{CATALOG.name}</option>
									<!-- END: category -->
								</select>
								<!-- BEGIN: error_category--><div class="text-danger">{error_category}</div><!-- END: error_category -->
								
							</div>
						</div>
						
						<div class="form-group">
							 <label class="col-sm-4 control-label" for="input-description">{LANG.album_description} </label>
							 <div class="col-sm-20">
								  <textarea name="description" rows="2" placeholder="{LANG.album_description}" id="input-description" class="form-control">{DATA.description}</textarea>
								  <!-- <span class="text-middle"> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </span> -->            
							  </div>
						 </div>
						 <div class="form-group required">
								<label class="col-sm-4 control-label" for="input-meta-title">{LANG.album_meta_title}</label>
								<div class="col-sm-20">
									<input type="text" name="meta_title" value="{DATA.meta_title}" placeholder="{LANG.album_meta_title}" id="input-meta-title" class="form-control" />
									<!-- BEGIN: error_meta_title--><div class="text-danger">{error_meta_title}</div><!-- END: error_meta_title -->
								</div>
						 </div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-meta-description">{LANG.album_meta_description}</label>
							<div class="col-sm-20">
								<textarea name="meta_description" rows="2" placeholder="{LANG.album_meta_description}" id="input-meta-description" class="form-control">{DATA.meta_description}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-meta-keyword">{LANG.album_meta_keyword}</label>
							<div class="col-sm-20">
								<textarea name="meta_keyword" rows="2" placeholder="{LANG.album_meta_keyword}" id="input-meta-keyword" class="form-control">{DATA.meta_keyword}</textarea>
							</div>
						</div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-model">{LANG.album_model}</label>
								<div class="col-sm-20">
									<input type="text" name="model" value="{DATA.model}" placeholder="{LANG.album_model}" id="input-model" class="form-control" />
 								</div>
						</div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-capturedate">{LANG.album_capturedate}</label>
								<div class="col-sm-20">
									<input type="text" name="capturedate" value="{DATA.capturedate}" placeholder="{LANG.album_capturedate}" id="input-capturedate" class="form-control" maxlength="10"/>
 								</div>
						</div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="input-capturelocal">{LANG.album_capturelocal}</label>
								<div class="col-sm-20">
									<input type="text" name="capturelocal" value="{DATA.capturelocal}" placeholder="{LANG.album_capturelocal}" id="input-capturelocal" class="form-control" />
 								</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-parent">{LANG.album_layout}</label>
							<div class="col-sm-20">
								<select class="form-control" name="layout">
									<option value="">{LANG.defaults}</option>
									<!-- BEGIN: layout -->
									<option value="{LAYOUT.key}" {LAYOUT.selected}>{LAYOUT.key}</option>
									<!-- END: layout -->
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-keyword"> {GLANG.groups_view}</label>
							<div class="col-sm-20">
								<!-- BEGIN: groups_view -->
								 
								<label><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.value}" {GROUPS_VIEW.checked} />{GROUPS_VIEW.title}</label>
								 
								<!-- END: groups_view -->
							</div>
						</div>	 
						 
									 
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-status">{LANG.album_show_status}</label>
							<div class="col-sm-20">
								<select name="status" id="input-status" class="form-control">
									<!-- BEGIN: status -->
									<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
									<!-- END: status -->
								</select>
							</div>
						</div>
					</div>
				
					<div class="tab-pane" id="tab-image">
							<ul class="glt-upload-step clearfix">
								<li >
									<span class="stepicon"><span>&nbsp;</span></span>
									<span class="steptext">Bước 1: Tạo Album</span>
								</li>
								<li class="active">
									<span class="stepicon"><span>&nbsp;</span></span>
									<span class="steptext">Bước 2: Chọn và tải ảnh</span>
								</li>
								<li>
									<span class="stepicon"><span>&nbsp;</span></span>
									<span class="steptext">Bước 3: Cập nhật thông tin ảnh</span>
								</li>
							</ul>
                            <div class="clearfix">&nbsp;</div>
							<div class="form-inline">
                                <strong>{LANG.select_dir}</strong>
                                <select id="dirname" class="form-control" name="dirname">
                                    <!--  BEGIN: dir  -->
                                    <option value="{DIR.key}">{DIR.name}</option>
                                    <!--  END: dir  -->
                                </select>
                                <input type="button" onclick="savefile()" class="btn btn-primary" value="Save" />
							</div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 filebrowse" id="upload-content">
                               <div class="clearfix" id="imglist" unselectable="on" style="-moz-user-select: none;"></div>
                               <script type="text/javascript">
                                     //&lt;![CDATA[
                                     $( "#dirname" ).change(function() {
                                        var did = $(this).val();
                                        if( did > 0 ){
                                            $('#imglist').load('{URL_LIST_IMG}&did=' + did);   
                                        }else{
                                            $('#imglist').html('');
                                        }
                                     });
                                    $('img.previewimg').lazyload({
                                    	placeholder : "{NV_BASE_SITEURL}images/grey.gif",
                                    	container : $(".filebrowse")
                                    });
                                    
                                    $('.imgcontent').bind("mouseup contextmenu", function(e) {
                                    	e.preventDefault();
                                    	fileMouseup(this, e);
                                    });
                                    
                                    $('.imgcontent').dblclick(function() {
                                    	insertvaluetofield();
                                    });
                                    //]]>
                                  </script>
                                  
                                    <script type="text/javascript">
                                    //<![CDATA[
                                    var LANG = [];
                                    
                                    LANG.upload_mode = "{LANG.upload_mode}";
                                    
                                    //]]>
                                    </script>
                                    <script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_NAME}/js/select_img.js"></script>
                                    <script type="text/javascript">
                                        function savefile(){
                                            var stt = {num_row};
                                            $('#upload-content .imgsel').each(function() 
                                        	{
                                                var html = "<div id=\"images-" + stt + "\" class=\"col-sm-12 col-md-6\">";
                                                html += 		"<div class=\"table-responsive row\">";
                                            	html +=				"<table class=\"table table-striped table-bordered table-hover\">";
                                            	html +=					"<tbody>";
                                            	html +=						"<tr>";
                                            	html +=							"<td class=\"col-md-2\">";
                                            	html +=								"<input type=\"hidden\" name=\"albums[filename][" + stt + "]\" value=\""+ $(this).attr('title') +"\">";
                                                html +=								"<input type=\"hidden\" name=\"albums[did][" + stt + "]\" value=\""+ $(this).attr('did') +"\">";
                                                html +=                             "<img src=\"" + $(this).attr('data-src') +"\" alt='' width=\"90\"></span>";
                                            	html +=							"</td>";
                                            	html +=							"<td class=\"col-md-10 control\">";
                                            	html +=								"<label class=\"labelradio\"><input type=\"radio\" name=\"albums[default][" + stt + "]\" value=\"1\" class=\"form-control fixradio\"> {LANG.defaults}</label>";	
                                            	html +=								"<label class=\"labelradio fr deleterows\" data-toggle=\"tooltip\" title=\"{LANG.delete}\" data-row=\"{PHOTO.row_id}\" data-key=\"" + stt + "\"\" >";
                                            	html +=									"<i class=\"fa fa-spinner fa-lg  fa-spin\"></i>";
                                            	html +=								     "<i class=\"fa fa-trash-o fa-lg fixtrash\"></i>";
                                            	html +=								"</label>";	
                                            	html +=								"<input type=\"text\" name=\"albums[name][" + stt + "]\" value=\"" + $(this).attr('data-alt') + "\" class=\"form-control\" placeholder=\"{LANG.photo_name}\">";
                                            	html +=								"<input type=\"text\" name=\"albums[description][" + stt + "]\" value=\"" + $(this).attr('data-alt') + "\" class=\"form-control\" placeholder=\"{LANG.photo_description}\">";
                                                html +=							"</td>";
                                            	html +=						"</tr>";
                                            	html +=					"</tbody>";
                                            	html +=				"</table>";
                                            	html +=			"</div>";
                                            	html +=			"</div>";
                                                $('#insert-image').append( html );
                                                stt++;
                                        	});
                                        }
                                    </script>
                            </div>						
					</div>
					<div class="tab-pane" id="tab-info-image">
						<ul class="glt-upload-step clearfix">
							<li>
								<span class="stepicon"><span>&nbsp;</span></span>
								<span class="steptext">Bước 1: Tạo Album</span>
							</li>
							<li>
								<span class="stepicon"><span>&nbsp;</span></span>
								<span class="steptext">Bước 2: Chọn và tải ảnh</span>
							</li>
							<li class="active">
								<span class="stepicon"><span>&nbsp;</span></span>
								<span class="steptext">Bước 3: Cập nhật thông tin ảnh</span>
							</li>
						</ul>
						<div class="clear"></div>
						<div class="containers">
 							<div class="message_info alert alert-danger" style="display:none">
								<i class="fa fa-exclamation-circle"></i> {LANG.album_error_defaults}
								<button type="button" class="close" data-dismiss="alert">×</button>
								<br>
							</div>
							<div class="row" id="insert-image">
								<!-- BEGIN: photo -->
								<div id="images-{PHOTO.key}" class="col-sm-12 col-md-6">
									<div class="table-responsive row">
										<table class="table table-striped table-bordered table-hover">
											<tbody>
												<tr>
													<td class="col-md-2">
														<input type="hidden" name="albums[filename][{PHOTO.key}]" value="{PHOTO.file}" />
														<input type="hidden" name="albums[did][{PHOTO.key}]" value="{PHOTO.did}" />
														<span><img src="{PHOTO.thumb}" width="90" /></span>
													</td>
													<td class="col-md-10 control">
														<label class="labelradio"><input type="radio" name="albums[default][{PHOTO.key}]" value="1" class="form-control fixradio" {PHOTO.defaults}> {LANG.defaults}</label>	
														<label class="labelradio fr deleterows" data-toggle="tooltip" title="{LANG.delete}" data-row="{PHOTO.row_id}" data-key="{PHOTO.key}" >
															<i class="fa fa-spinner fa-lg  fa-spin"></i>
															<i class="fa fa-trash-o fa-lg fixtrash"></i>
														</label>	
														<input type="text" name="albums[name][{PHOTO.key}]" value="{PHOTO.name}" class="form-control" placeholder="{LANG.photo_name}">
														<input type="text" name="albums[description][{PHOTO.key}]" value="{PHOTO.description}" class="form-control" placeholder="{LANG.photo_description}"> </td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>			
								<!-- END: photo -->
							</div>
						</div>
					</div>
				</div>				
				<div align="center">
					<input type="hidden" name ="album_id" value="{DATA.album_id}" />
					<input name="action" type="hidden" value="add" />
					<input name="save" type="hidden" value="1" />
					<!-- <input class="btn btn-primary" type="submit" value="{LANG.save}" /> -->
					<!-- <a class="btn btn-primary" href="{CANCEL}" title="{LANG.cancel}">{LANG.cancel}</a>  -->
				</div>
                     
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js"></script>
<script type="text/javascript">

$('#input-capturedate').datepicker({
	showOn : "both",
	dateFormat : "dd/mm/yy",
	changeMonth : true,
	changeYear : true,
	showOtherMonths : true,
	buttonImage : nv_base_siteurl + "images/calendar.gif",
	buttonImageOnly : true
});
 
function checkform()
{
	$('.text-danger').remove();
	var is = true;	
	var name = $('input[name="name"]');	
	if( name.val().length < 3 )
	{		
		name.after('<div class="text-danger">{LANG.album_error_name}</div>');
		is = false;
	}else
	{
		var ename = $(name).parent().parent();	
		if (ename.hasClass('required')) {
			ename.removeClass('has-error');
		}
	}	
	var category = $('select[name="category_id"]');
	if( category.val() == 0 )
	{
		category.after('<div class="text-danger">{LANG.album_error_category}</div>');
		is = false;
	}else
	{
		var ecategory = $(category).parent().parent();	
		if (ecategory.hasClass('required')) {
			ecategory.removeClass('has-error');
		}
	}
	
	var meta_title = $('input[name="meta_title"]');
	if( meta_title.val().length < 3 )
	{
		meta_title.after('<div class="text-danger">{LANG.album_error_meta_title}</div>');
		is = false;
	}else
	{
		var emeta_title = $(meta_title).parent().parent();	
		if (emeta_title.hasClass('required')) {
			emeta_title.removeClass('has-error');
		}
	}
	
	
	$('body .text-danger').each(function() {
		var element = $(this).parent().parent();
		
		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});
	
	
	if( ! is ) 
	{
		$('a[rel="tab-image"]').parent().addClass('disabled');
		$('a[rel="tab-info-image"]').parent().addClass('disabled');
		return false;
	}else 
	{
		$('.text-danger').remove();
		$('a[rel="tab-image"]').parent().removeClass('disabled');
		$('a[rel="tab-info-image"]').parent().removeClass('disabled');
	}
	return is;
}

$('a[rel="tab-image"], a[rel="tab-info-image"]').hover( function(e) {	
	return checkform();
});
$('a[rel="tab-image"], a[rel="tab-info-image"], input[type="submit"], button[type="submit"], input[type="text"], select[name="category_id"]').on('click keyup blur change', function(e) {	
	return checkform();
});
$('body').on('click', '.fixradio', function(e) {	
 
	$('body .fixradio').each(function() 
	{
		$(this).prop('checked', false);
	});
	$(this).prop('checked', true);
});

$('body').on('click', '.deleterows', function(e) {	
	var album_id = '{DATA.album_id}';
	var row_id = $(this).attr('data-row');
	var key = $(this).attr('data-key');
	if(confirm('{LANG.confirm}') ) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=album&action=deleterows&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'album_id=' + album_id + '&row_id=' + row_id,
			beforeSend: function() {
				$('#images-' + key + ' .deleterows .fa-spinner').css('display', 'block');
			},	
			complete: function() {
				$('#images-' + key + ' .fa-spinner').css('display', 'none');
			},
			success: function(json) {
				$('.alert').remove();
				$("html, body").animate({ scrollTop: 0 }, "slow");
				
				if (json['error']) {
                    $('#content').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					
					$('#images-' + key).remove();
					
					$('#content').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					
					if( $('input[name="albums['+key+'][defaults]"]' ).is(":checked") )
					{
						$('body .fixradio').get(0).checked = true;
					}else
					{
						var check = 0;
						$('body .fixradio').each(function() 
						{
							 if( $(this).is(":checked") )
							 {
								++check;
							 }
						});
						if( check == 0 )
						{
							$('body .fixradio').get(0).checked = true;
						}
					}
					
				}		
				 
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	} 
});
 
$('button[type=\'submit\']').on('click', function() 
{
	var checked = 0;
	$('body .fixradio').each(function() 
	{
		if( $(this).is(':checked') )
		{
			++checked;
		}
	});
	if( checked == 0 )
	{
		$('.message_info').show();
		alert('{LANG.check_form}');
		return false;
	}else
	{
		$('.message_info').hide();
	}
	
	if( checkform() == true )
	{
		$("form[id*='form-']").submit();
	}
	
});
 
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/footer.js"></script>

<!-- BEGIN: getalias -->
<script type="text/javascript">
//<![CDATA[
$("#input-name").change(function() {
	get_alias('album', {DATA.album_id});
});
 
//]]>
</script>
<!-- END: getalias -->
<!-- END: main -->