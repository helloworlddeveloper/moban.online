<!-- BEGIN: main -->
<script type="text/javascript">
	var bc_cho = "{LANG.bc_chon}";
	var de_duyet_cofirm = "{LANG.de_duyet_cofirm}";
	var nv_not_duyet = "{LANG.nv_not_duyet}";
	var doempty = "{LANG.doempty}";
</script>

<!-- BEGIN: who_view -->
<a title="{LANG.book_question}" href="{LINK}" class="btn btn-warning pull-right">{LANG.book_question}</a><br /><br />
<!-- END: who_view -->

<div class="panel panel-default">
	<div class="panel-body">
		<h3><a title="{ROW.title}" href="{ROW.link}"> {ROW.title} </a></h3>
		<h5 class="help-block"> {ROW.cus_name} <!-- BEGIN: email --> (<a href="mailto:{ROW.cus_email}" title="Mail to: {ROW.cus_email}">{ROW.cus_email}</a>) <!-- END: email -->| {ROW.addtime} </h5>
		<p>{ROW.question}</p>
	</div>
</div>

<!-- BEGIN: an -->
<h4 class="text-danger"><strong>{LANG.traloi}</strong></h4>

<!-- BEGIN: loop -->
<blockquote>
	<h4 class="help-block">{LOOP.cus_name} &nbsp;-&nbsp; <a href="mailto:{LOOP.cus_email}" title="Mail to {LOOP.cus_email}">{LOOP.cus_email}</a> | {LOOP.addtime}</h4>
	<p>{LOOP.answer}</p>
	<!-- BEGIN: file -->
	<p>
		<span class="text-danger"> {LANG.file}: </span><a id="myfile{LOOP.id}" href="{LOOP.links}" onclick="nv_download_files('{LOOP.links}');return false;">{LOOP.titles}</a>
	</p>
	<!-- END: file -->
	<!-- BEGIN: bchn -->
	<span class="pull-right"><img border="0" alt="{LANG.mostbc}" title="{LANG.mostbc}" src="{NV_BASE_SITEURL}themes/{themes}/images/faqs/star.png"></span>
	<!-- END: bchn -->
	<!-- BEGIN: bc -->
	<span class="pull-right"><a  href="javascript:void(0);" onclick="nv_bc('{LOOP.id}','{ROW.alias}');return false;" style="float: right;"> <img border="0" alt="{LANG.numbcan}" title="{LANG.numbcan}" src="{NV_BASE_SITEURL}themes/{themes}/images/faqs/bc.png"></span></a>
	<!-- END: bc -->
</blockquote>
<hr />
<!-- END: loop -->
<!-- END: an -->

<!-- BEGIN: anss -->
<h4 class="text-danger"><strong>{LANG.traloiss}</strong></h4>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div id="load_taret" style="display: none;text-align:center"></div>
<form action="{ACTION_FILE}" id="frm" method="post">
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<td>{LANG.full_name} <span class="red">*</span> </td><td><input required="true" type="text" size="40" value="{full_name}" id="full_name" name="full_name" class="form-control" /> </td>
		</tr>
		<tr>
			<td>{LANG.email} <span class="red">*</span> </td><td><input required="true" type="email" size="40" value="{email}" id="email" name="email" class="form-control" /> </td>
		</tr>
		<tr>
			<td valign="top" align="left" colspan="2"> {HTML_ND} </td>
		</tr>
		<!-- BEGIN: captcha -->
		<tr>
			<td>{LANG.captcha} <span class="red">*</span> </td>
			<td>
				<img height="22" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{LANG.captcha}" alt="{LANG.captcha}" id="vimg" />
				<img alt="{CAPTCHA_REFRESH}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','fcode_iavim');"  alt="thay doi"/>
				<input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" class="form-control capcha pull-left" style="width: 120px" />
			</td>
		</tr>
		<!-- END: captcha -->
		<tr>
			<td valign="top" align="left" colspan="2">
			<input type="hidden" name="type" value="sendemail" />
			<input type="reset" value="{LANG.xoa}" class="btn btn-danger" />
			<input type="submit" value="{LANG.sends}" name="" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>
<iframe id="upload_target" name="upload_target" src="#" style="width:100px;height:1px;border:1px solid #fff;"></iframe>
<!-- END: anss -->

<!-- BEGIN: othernews -->
<h3 class="text-danger">{LANG.othernews}</h3>
<div class="other_blocknews">
	<ul style="padding: 0">
		<!-- BEGIN: loops -->
		<li><em class="fa fa-angle-right">&nbsp;</em><a href="{LOOP.link}" title="{LOOP.title}">{LOOP.title}</a> </li>
		<!-- END: loops -->
	</ul>
</div>
<!-- END: othernews -->

<script type="text/javascript">
	function nv_complete(message) {
		if (message == "OK") {
			$("#load_taret").hide();
			$("textarea[name=answer]").val('');
			$("input[name=fileupload]").val('');
			alert("{LANG.com_send}");
		} else {
			$("#load_taret").html('<img src="' + nv_siteroot + 'images/load_bar.gif" alt=""/>').hide();
			$('#frm').show();
			alert(message);
		}
		return !1;
	}

	function sendemail() {
		var d = $("textarea[name=answer]").val();
		var id = $("textarea[name=id]").val();
		var h = document.getElementById('email');

		$("#load_taret").html('<img src="' + nv_siteroot + 'images/load_bar.gif" alt=""/>').show();
		$('#frm').hide();
		return true;
	}
</script>
<!-- END: main -->