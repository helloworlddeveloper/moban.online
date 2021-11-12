<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/default/modules/{MODULE_FILE}/plugins/shadowbox/shadowbox.css" />
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
				<button type="submit" data-toggle="tooltip" id="album_save" name="album_save" class="btn btn-primary" title="{LANG.save}"><i class="fa fa-save"></i></button> 
				<a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="album-add" class="form-horizontal">
				<ul class="nav nav-tabs">
				    <li class="active"><a href="#tab-image" rel="tab-image" data-toggle="tab">{LANG.album_image}</a></li>
                    <li class="disabled"><a href="#tab-info-image" rel="tab-info-image" data-toggle="tab">{LANG.album_info_image}</a></li>
                </ul>
				<div class="tab-content">
					<div class="active tab-pane" id="tab-image">
							<div class="form-inline">
								<div id="uploader">
									<p>{LANG.album_upload_require}</p>
								</div>
							</div>
							<a href="javascript:void(0);" class="nextstep btn btn-primary">{LANG.album_next_step} </a>
							<link type="text/css" href="{NV_BASE_SITEURL}themes/admin_default/modules/{MODULE_FILE}/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" rel="stylesheet" />
							<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/modules/{MODULE_FILE}/plupload/plupload.full.min.js"></script>
							<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/modules/{MODULE_FILE}/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
							<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/modules/{MODULE_FILE}/plupload/i18n/vi.js"></script>
							<script type="text/javascript">
								$(function() {
									$("#uploader").pluploadQueue({
										runtimes: 'html5,flash,silverlight,html4',
										url: '{UPLOAD_URL}',
										<!-- BEGIN: resize_at_browser -->
										resize: {
										  width: {ORIGIN_WIDTH},
										  height: {ORIGIN_HEIGHT}
										},
										<!-- END: resize_at_browser -->
										chunk_size: '{MAXUPLOAD}',
										max_retries: 3,
										rename: false,
										dragdrop: true,
										filters: {
											max_file_size: '{MAXUPLOAD}',
											mime_types: [{
												title: "Image files",
												extensions: "jpg,gif,png,jpeg"
											}, ]
										},
										flash_swf_url: '{NV_BASE_SITEURL}themes/admin_default/modules/{MODULE_FILE}/plupload/Moxie.swf',
										silverlight_xap_url: '{NV_BASE_SITEURL}themes/admin_default/modules/{MODULE_FILE}/plupload/Moxie.xap',
										multi_selection: true,
										prevent_duplicates: true,
										multiple_queues: false,
										init: {

											FilesAdded: function (up, files) {},
											UploadComplete: function (up, files) {
												//$('.plupload_filelist_footer .plupload_file_name').append('<a href="javascript:void(0);" class="plupload_button plupload_submit nextstep">{LANG.album_next_step}</a>'); 
												$(".plupload_button").css("display", "inline");
												$(".plupload_upload_status").css("display", "inline");
												Shadowbox.init({ skipSetup: true }); Shadowbox.setup(); 
												 
 											}
										}
										   
									});
									var uploader = $("#uploader").pluploadQueue();  
									uploader.bind('BeforeUpload', function(up) {
										 up.settings.multipart_params = {
												'folder': $('input[name="folder"]').val()
										 };
									});
									var i = {num_row};
									uploader.bind('FileUploaded', function(up, file, response) {
										
 										var content = $.parseJSON(response.response).data;
 
										var item='';		  
										item+='<div id="images-'+ i +'" class="col-sm-12 col-md-8">';
										item+='<div class="table-responsive row">';
										item+='	<table class="table table-striped table-bordered table-hover">';
										item+='		<tbody>';
										item+='			<tr>';
										item+='				<td class="col-md-2">';
										item+='					<input type="hidden" name="albums['+ i +'][row_id]" value="0">';
										item+='					<input type="hidden" name="albums['+ i +'][basename]" value="'+ content['basename'] +'">';
										item+='					<input type="hidden" name="albums['+ i +'][filePath]" value="'+ content['filePath'] +'">';
										item+='					<input type="hidden" name="albums['+ i +'][image_url]" value="'+ content['image_url'] +'">';
										item+='					<input type="hidden" name="albums['+ i +'][thumb]" value="'+ content['thumb'] +'">';
										item+='					<a href="'+ content['image_url'] +'" rel="shadowbox[miss]" class="glt-upload2-thumb">';
										item+='						<span><img src="'+ content['thumb'] +'" width="90"></span>'; 
										item+='					</a>';
										item+='				</td>';
										item+='				<td class="col-md-10 control">';
										item+='					<label class="labelradio fr deleterows" data-toggle="tooltip" title="{LANG.delete}" data-row="'+ content['row_id'] +'" data-token="'+ content['token'] +'" data-token-image="'+ content['token_image'] +'" data-token-thumb="'+ content['token_thumb'] +'" data-key="'+ i +'" >';
										item+='						<i class="fa fa-spinner fa-lg  fa-spin"></i>';
										item+='						<i class="fa fa-trash-o fa-lg fixtrash"></i>';
										item+='					</label>';
										item+='					<input type="text" name="albums['+ i +'][name]" value="' + content['basename'] + '" class="form-control" placeholder="{LANG.photo_name}">';
										item+='					<input type="text" name="albums['+ i +'][description]" value="" class="form-control" placeholder="{LANG.photo_description}">';
										item+='				</td>';
										item+='			</tr>';
										item+='		</tbody>';
										item+='	</table>';
										item+='</div>';
										item+='</div>';
										
										$('#insert-image').append(item);										
										++i;  
										  
									});
									
									uploader.bind("UploadComplete", function () {
										$('.nextstep').css("display", "inline-block");
									});
									
									uploader.bind('QueueChanged', function() {
										$('.nextstep').css("display", "none");
									});
 
								 	
									$('.nextstep').on('click', function(){
										$('a[rel="tab-info-image"]').tab('show'); 
										uploader.splice();
										uploader.refresh();
										uploader.init( );
										$('.plupload_buttons').css("display", "inline");
										$(".plupload_upload_status").css("display", "inline");
									});
								});
							</script>
					</div>
					<div class="tab-pane" id="tab-info-image">
						<div class="clear"></div>
						<div class="containers">
 							<div class="message_info alert alert-danger" style="display:none">
								<i class="fa fa-exclamation-circle"></i> {LANG.album_error_defaults}
								<button type="button" class="close" data-dismiss="alert">×</button>
								<br>
							</div>
							<div class="row" id="insert-image">
								<!-- BEGIN: photo -->
								<div id="images-{PHOTO.key}" class="col-sm-12 col-md-8">
									<div class="table-responsive row">
										<table class="table table-striped table-bordered table-hover">
											<tbody>
												<tr>
													<td class="col-md-2">
														<input type="hidden" name="albums[{PHOTO.key}][row_id]" value="{PHOTO.row_id}">
														<input type="hidden" name="albums[{PHOTO.key}][basename]" value="{PHOTO.basename}">
														<input type="hidden" name="albums[{PHOTO.key}][filePath]" value="{PHOTO.filePath}">
														<input type="hidden" name="albums[{PHOTO.key}][image_url]" value="{PHOTO.image_url}">
														<input type="hidden" name="albums[{PHOTO.key}][thumb]" value="{PHOTO.thumb}">
														<a href="{PHOTO.image_url}" rel="shadowbox[miss]" class="glt-upload2-thumb"> <span><img src="{PHOTO.thumb}" width="90"></span> </a>
													</td>
													<td class="col-md-10 control">
														<label class="labelradio fr deleterows" data-toggle="tooltip" title="{LANG.delete}" data-row="{PHOTO.row_id}" data-token="{PHOTO.token}" data-token-image="{PHOTO.token_image}" data-token-thumb="{PHOTO.token_thumb}" data-key="{PHOTO.key}" >
															<i class="fa fa-spinner fa-lg  fa-spin"></i>
															<i class="fa fa-trash-o fa-lg fixtrash"></i>
														</label>
														<input type="text" name="albums[{PHOTO.key}][name]" value="{PHOTO.name}" class="form-control" placeholder="{LANG.photo_name}">
														<input type="text" name="albums[{PHOTO.key}][description]" value="{PHOTO.description}" class="form-control" placeholder="{LANG.photo_description}"> </td>
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
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/modules/{MODULE_FILE}/plugins/shadowbox/shadowbox.js"></script>
<script type="text/javascript">Shadowbox.init();</script>

<script type="text/javascript">
var album_id = '{DATA.album_id}';
var lang_confirm = '{LANG.confirm}';
var lang_check_form = '{LANG.check_form}';

</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/photos_footer.js"></script>
<!-- BEGIN: getalias -->
<script type="text/javascript">
$("#input-name").change(function() {
	get_alias('album', {DATA.album_id});
	get_alias_folder('folder', {DATA.album_id});
});
</script>
<!-- END: getalias -->

<script type="text/javascript">

$("button[id*='album']").on('click', function() 
{
    $("form[id*='album-']").submit();
});
</script>
<!-- END: main -->