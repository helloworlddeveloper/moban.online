<!-- BEGIN: main -->
<div id="ablist">
	<select class="form-control" name="dList">
		<option value="">{LANG.catselect}</option>
		<!-- BEGIN: psopt2 -->
		<option value="{OPTION2.id}">{OPTION2.name}</option>
		<!-- END: psopt2 -->
	</select>
	<input style="margin-right:50px" name="ok" type="button" value="OK" />
	<input name="addNew" type="button" value="{LANG.addNews}" />
</div>
<div class="myh3">
	<span><a class="dep" href="0">{LANG.main}</a></span>
</div>
<div id="pageContent"></div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("div#pageContent").load("{MODULE_URL}&list&random=" + nv_randomPassword(10))
	});
	$("input[name=addNew]").click(function() {
		window.location.href = "{MODULE_URL}&add";
		return false
	});
	$("input[name=ok]").click(function() {
		var a = $("select[name=dList]").val();
		if (a != "") {
			$("a.dep").first().parent().nextAll().remove();
			a = a.split("|");
			$("div.myh3").append('<span> &raquo; <a class="dep" href="cat=' + a[0] + '">' + a[1] + "</a></span>");
			$("div#pageContent").load("{MODULE_URL}&list&cat=" + a[0] + "&random=" + nv_randomPassword(10))
		}
		$("select[name=dList]").val("");
		return false
	});
	//]]>
</script>
<!-- END: main -->
<!-- BEGIN: add -->

<script src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-vi.js" type="text/javascript"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />

<h3 class="myh3">{INFO_TITLE}</h3>
<div class="red">
	{ERROR_INFO}
</div>
<form class="form-inline" id="addInformation" method="post" action="{POST.action}">
	<table class="table table-striped table-bordered table-hover">
		<col style="width:220px" />
		<tbody>
			<tr>
				<td> {LANG.title} <span style="color:red">*</span></td>
				<td>
				<input class="form-control" title="{LANG.title}" type="text" name="title" value="{POST.title}" style="width:400px" maxlength="255" />
				</td>
			</tr>
			<tr>
				<td> {LANG.cattitle} </td>
				<td>
				<select class="form-control" name="catid">
					<option value="0"> - Chọn loại thông báo --</option>
					<!-- BEGIN: option -->
					<option value="{OPTION.value}"{OPTION.selected}>{OPTION.name}</option>
					<!-- END: option -->
				</select></td>
			</tr>
			<tr>
				<td> {LANG.duonglink} </td>
				<td>
				<input class="form-control" title="{LANG.duonglink}" type="text" name="link" value="{POST.link}" style="width:400px" maxlength="100" />
				</td>
			</tr>
			<tr>
				<td> {LANG.pubtime} </td>
				<td>
				<input class="form-control" name="pubtime" id="pubtime" value="{pubtime}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
				<select class="form-control" name="phour">
					{phour}
				</select> :
				<select class="form-control" name="pmin">
					{pmin}
				</select></td>
			</tr>
			<tr>
				<td> {LANG.exptime} </td>
				<td>
				<input class="form-control" name="exptime" id="exptime" value="{exptime}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
				<select class="form-control" name="ehour">
					{ehour}
				</select> :
				<select class="form-control" name="emin">
					{emin}
				</select></td>
			</tr>
		</tbody>

	</table>
	<div>
		{LANG.content}
	</div>
	<div>
		{CONTENT}
	</div>
	<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
</form>
<script type="text/javascript">
	//<![CDATA[
	$("#pubtime,#exptime").datepicker({
		showOn : "button",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
	$("input[name=img]").dblclick(function() {
		var a = $(this).val(), b = this;
		a = trim(a);
		a != "" && $.ajax({
			type : "POST",
			url : window.location.href,
			data : "delectImage=" + a,
			success : function(c) {
				if (c == "error") {
					alert("{LANG.errorImageUrl}");
					$(b).select()
				} else {
					c = c.split("|");
					Shadowbox.open({
						content : '<div style="background:#fff"><img src="' + a + '" alt="" /></div>',
						player : "html",
						height : c[1],
						width : c[0]
					})
				}
			}
		})
	});
	$("form#addInformation").submit(function() {
		var a = trim($("input[name=title]").val());
		$("input[name=title]").val(a);
		if (a == "") {
			alert("{LANG.errorTitleEmpty}");
			$("input[name=title]").val("").select();
			return false
		}
		a = trim($("textarea[name=hometext]").val());
		$("textarea[name=hometext]").val(a);
		if (a == "") {
			alert("{LANG.errorHometextEmpty}");
			$("textarea[name=hometext]").val("").focus();
			return false
		}
		$("form#addInformation").hide().submit();
		return false
	});
	//]]>
</script>
<!-- END: add -->
<!-- BEGIN: list -->
<table class="table table-striped table-bordered table-hover">
	<col style="width:120px" />
	<thead>
		<tr>
			<td> {LANG.pos} </td>
			<td> {LANG.pubtime} </td>
			<td> {LANG.title} </td>
			<td> {LANG.cattitle} </td>
			<td> {LANG.content} </td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: loop -->
		<tr>
			<td>
			<select name="p_{DATA.id}" class="form-control newWeight">
				<!-- BEGIN: option -->
				<option value="{NEWWEIGHT.value}"{NEWWEIGHT.selected}>{NEWWEIGHT.value}</option>
				<!-- END: option -->
			</select></td>
			<td> {DATA.pubtime} </td>
			<td> {DATA.title} </td>
			<td><a class="yessub" href="cat={DATA.catid}">{DATA.catname}</a></td>
			<td> {DATA.html} </td>
			<td style="text-align:right"><a href="{DATA.id}" title="{DATA.status}" class="changeStatus"><img style="vertical-align:middle;margin-right:10px" alt="{DATA.status}" title="{DATA.status}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{module}/{DATA.icon}.png" width="12" height="12" /></a><a href="{MODULE_URL}&edit&id={DATA.id}">{GLANG.edit}</a> | <a class="del" href="{DATA.id}">{GLANG.delete}</a></td>
		</tr>
	<!-- END: loop -->
	</tbody>
</table>
<div id="nv_generate_page">
	{NV_GENERATE_PAGE}
</div>
<script type="text/javascript">
	//<![CDATA[
	$("a.yessub").click(function() {
		$("a.dep").first().parent().nextAll().remove();
		var a = $(this).attr("href");
		$("div.myh3").append('<span> &raquo; <a class="dep" href="' + a + '">' + $(this).text() + "</a></span>");
		$("div#pageContent").load("{MODULE_URL}&list&" + a + "&random=" + nv_randomPassword(10));
		return false
	});
	$("select.newWeight").change(function() {
		var a = $(this).attr("name").split("_"), c = $(this).val(), d = this;
		a = a[1];
		$(this).attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}",
			data : "cWeight=" + c + "&id=" + a,
			success : function(b) {
				if (b == "OK") {
					$("div#pageContent").load("{MODULE_URL}&list&random=" + nv_randomPassword(10))
				} else {
					alert("{LANG.errorChangeWeight}")
				}
				$(d).removeAttr("disabled")
			}
		});
		return false
	});
	$("a.dep").click(function() {
		$(this).parent().nextAll().remove();
		var a = $(this).attr("href");
		a = a != "0" ? "&" + a : "";
		$("div#pageContent").load("{MODULE_URL}&list" + a + "&random=" + nv_randomPassword(10));
		return false
	});
	$("div#nv_generate_page a").click(function() {
		var a = $(this).attr("href");
		$("div#pageContent").load(a + "&random=" + nv_randomPassword(10));
		return false
	});
	$("a.del").click(function() {
		confirm("{LANG.delConfirm} ?") && $.ajax({
			type : "POST",
			url : "{MODULE_URL}",
			data : "del=" + $(this).attr("href"),
			success : function(a) {
				a == "OK" ? window.location.href = window.location.href : alert(a)
			}
		});
		return false
	});
	$("a.changeStatus").click(function() {
		var t = this;
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}",
			data : "changeStatus=" + $(this).attr("href"),
			success : function(a) {
				$(t).html(a);
			}
		});
		return false
	});

	//]]>
</script>
<!-- END: list -->