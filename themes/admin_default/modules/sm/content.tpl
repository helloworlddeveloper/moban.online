<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<input type="hidden" value="{ISCOPY}" name="copy" />
	<div class="row">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col class="w200" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<td class="text-right"> {LANG.title} <sup class="required">(*)</sup></td>
						<td><input class="w300 form-control pull-left" type="text" value="{DATA.title}" name="title" maxlength="250" /></td>
					</tr>
					<tr>
						<td class="text-right"> {LANG.title_english} <sup class="required">(*)</sup></td>
						<td><input class="w300 form-control pull-left" type="text" value="{DATA.title_english}" name="title_english" maxlength="250" /></td>
					</tr>
					<tr>
						<td class="text-right">{LANG.image}</td>
						<td><input class="w300 form-control pull-left" type="text" name="image" id="image" value="{DATA.image}" style="margin-right: 5px"/><input type="button" value="Browse server" name="selectimg" class="btn btn-info"/></td>
					</tr>
					<tr>
						<td class="text-right"> {LANG.link} <sup class="required">(*)</sup></td>
						<td><input class="w300 form-control pull-left" type="text" value="{DATA.link}" name="link" maxlength="250" /></td>
					</tr>
					<tr>
						<td class="text-right"> {LANG.price} <sup class="required">(*)</sup></td>
						<td><input class="w300 form-control pull-left" type="text" value="{DATA.price}" name="price" maxlength="250" /></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row text-center"><input type="submit" value="{LANG.save}" class="btn btn-primary"/>
	</div>
</form>
<script>
    var uploads_dir_user = '{UPLOADS_DIR_USER}';
</script>
<!-- END: main -->