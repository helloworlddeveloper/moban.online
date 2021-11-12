<!-- BEGIN: main -->
<style type="text/css">
    #uploadForm_file {border-top:#F0F0F0 2px solid;background:#FAF8F8;padding:10px;}
    #uploadForm_file label {margin:2px; font-size:1em; font-weight:bold;}
    .demoInputBox{padding:5px; border:#F0F0F0 1px solid; border-radius:4px; background-color:#FFF;}
    #progress-bar {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
    .btnSubmit{background-color:#09f;border:0;padding:10px 40px;color:#FFF;border:#F0F0F0 1px solid; border-radius:4px;}
    #progress-div {border:#0FA015 1px solid;padding: 5px 0px;margin:30px 0px;border-radius:4px;text-align:center;}
    #targetLayer{width:100%;text-align:center;}
</style>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.ui.widget.js"></script>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.iframe-transport.js"></script>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.fileupload.js" type="text/javascript"></script>
<div class="page_title">
	<div class="right2">
		<a href="{DOWNLOAD}">{LANG.download}</a>
	</div>
	{LANG.upload}
</div>
<!-- BEGIN: is_error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: is_error -->

<form id="uploadForm" name="uploadForm" action="{FORM_ACTION}" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
    <input type="hidden" value="{UPLOAD.id}" name="id" />
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_title}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_title" id="upload_title" value="{UPLOAD.title}">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.link_file}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="link_file" id="link_file" value="{UPLOAD.link_file}">
		</div>
	</div>
	<!-- BEGIN: is_upload_allow -->
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.upload_files}</label>
		<div class="col-lg-10">
			<div class="input-group">
				<input type="text" class="form-control" id="file_name" disabled="disabled" value="{fileshare}" />
				<span class="input-group-btn">
				    <button class="btn btn-default" onclick="$('#upload_fileupload').click();" type="button"><em class="fa fa-folder-open-o fa-fix">&nbsp;</em> {LANG.file_selectfile}</button>
				</span>
			</div>
			<p class="help-block">{LANG.upload_valid_ext_info}: {EXT_ALLOWED}</p>
            <input type="hidden" value="{fileshare}" name="filename_uploaded" id="filename_uploaded" />
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
            <input type="file" name="upload_fileupload" id="upload_fileupload" style="visibility: hidden;" />
  		</div>
	</div>
	<!-- END: is_upload_allow -->
	<div class="form-group">
		<label class="col-sm-2 control-label">&nbsp;</label>
		<div class="col-sm-10">
			<input type="hidden" name="addfile" value="{UPLOAD.addfile}" />
			<input class="btn btn-primary" type="submit" id="submit" name="submit" value="{LANG.upload}" />
		</div>
	</div>
</form>

<script type="text/javascript">
	$('#upload_fileupload').change(function(){
	     $('#file_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});
    $('#submit').prop('disabled', 'disabled' );
	<!-- BEGIN: fileshare -->
    $('#submit').prop('disabled', '' );
    <!-- END: fileshare -->
/*jslint unparam: true */
/*global window, $ */
$(function () {
    $('#progress .progress-bar').html('');
    $('#upload_fileupload').fileupload({
        url: '{URL_UPLOAD}',
        dataType: 'json',
        done: function (e, data) {
            if( data.result.status == 0 ){
                $('#progress .progress-bar').css(
                    'color', '#f00'
                );
                $('#progress .progress-bar').html( data.result.message );
            }else{
                $('#filename_uploaded').val( data.result.filename );
            }
            $('#submit').prop('disabled', '' );
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    });
});
</script>
<!-- END: main -->