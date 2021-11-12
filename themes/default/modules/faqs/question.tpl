<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-danger">
	{error}
</div>
<!-- END: error -->

<h3 class="text-center text-success">{LANG.book_question}</h3>

<form action="{ACTION_FILE}" name="frm" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.title} <span class="red">*</span></label>
		<div class="col-sm-8">
			<input type="text" size="40" value="{CONTENT.title}" required="required" id="title" name="title" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.cat} <span class="red">*</span></label>
		<div class="col-sm-8">
			<select name="catid" class="form-control">
				<!-- BEGIN: catid -->
				<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.title}</option>
				<!-- END: catid -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.full_name} <span class="red">*</span></label>
		<div class="col-sm-8">
			<input type="text" size="40" required="required" value="{CONTENT.full_name}" id="full_name" name="full_name" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.email} <span class="red">*</span></label>
		<div class="col-sm-8">
			<input type="email" size="40" required="required" value="{CONTENT.email}" id="email" name="email" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.sendmail}</label>
		<div class="col-sm-8">
			<input type="checkbox" name="sendmail" {CONTENT.sendmail} value="1" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.showmail}</label>
		<div class="col-sm-8">
			<input type="checkbox" name="showmail" {CONTENT.showmail} value="1" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			{HTMLQS}
		</div>
	</div>

	<!-- BEGIN: captcha -->
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.captcha} <span class="red">*</span></label>
		<div class="col-sm-8">
			<img height="22" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{LANG.captcha}" alt="{LANG.captcha}" id="vimg" />
			<img alt="{CAPTCHA_REFRESH}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','fcode_iavim');"  alt="thay doi"/>
			<input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" class="form-control capcha pull-left" style="width: 120px" />
		</div>
	</div>
	<!-- END: captcha -->

	<div class="form-group">
		<label class="col-sm-4 control-label">&nbsp;</label>
		<div class="col-sm-8">
			<input type="reset" value="Reset" class="btn btn-danger" />
			<input type="submit" value="{LANG.send}" class="btn btn-primary" name="save"/>
		</div>
	</div>

</form>

<!-- END: main -->