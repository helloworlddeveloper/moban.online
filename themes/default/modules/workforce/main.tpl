<!-- BEGIN: main -->
<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" onsubmit="return search_data_users()" method="post">
	<div class="row">
		<div class="form-group">
			<div class="input-group col-md-18" style="float:left;padding-right: 10px">
				<input class="form-control" type="text" id="search_data" name="search_data" value="" placeholder="{LANG.search_data}">
			</div>
			<div class="input-group col-md-4"><input type="submit" class="btn btn-primary" value="{LANG.search}"></div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
			<tr>
				<td>
					<table class="table table-striped table-bordered table-hover">
						<tr>
							<th style="width: 100px">{LANG.code}</th>
							<td id="code">{VIEW.code}</td>
						</tr>
						<tr>
							<th>{LANG.biensoxe}</th>
							<td id="biensoxe">{VIEW.biensoxe}</td>
						</tr>
						<tr>
							<th>{LANG.first_name}</th>
							<td><span id="last_name">{VIEW.last_name}</span>&nbsp;<span id="first_name">{VIEW.first_name}</span></td>
						</tr>
						<tr>
							<th>{LANG.gender}</th>
							<td id="gender">{VIEW.gender}</td>
						</tr>
					</table>
				</td>
				<td style="width: 150px" class="text-right"><span id="image"></span></td>
			</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
    $( "#search_data" ).focus();
	$('#image').html('<img class="img-thumbnail bg-gainsboro" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/no-image.jpg" />');
</script>
<!-- END: main -->