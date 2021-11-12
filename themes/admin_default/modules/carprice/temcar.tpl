<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" maxlength="64" name="q" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<select class="form-control" name="producerid">
						<option value="0">--------</option>
						<!-- BEGIN: producer -->
						<option value="{PROCUDER.id}"{PROCUDER.sl}>{PROCUDER.title}</option>
						<!-- END: producer -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<select class="form-control" name="typecarid">
						<option value="0">--------</option>
						<!-- BEGIN: typecar -->
						<option value="{TYPECAR.id}"{TYPECAR.sl}>{TYPECAR.title}</option>
						<!-- END: typecar -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
				</div>
			</div>
		</div>
		<input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
	</form>
</div>
<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<td width="20px" class="text-center">&nbsp;</td>
			<td><strong>{LANG.temcar_name}</strong></td>
			<td><strong>{LANG.producer}</strong></td>
			<td><strong>{LANG.typecar}</strong></td>
			<td><strong>{LANG.numseats}</strong></td>
			<td><strong>{LANG.price_listing}</strong></td>
			<td><strong>{LANG.price_negotiate}</strong></td>
			<td width="120px" class="text-center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: row -->
		<tr>
			<td><input type="checkbox" class="ck" value="{id}" /></td>
			<td>{DATA.title}</td>
			<td>{DATA.producerid}</td>
			<td>{DATA.typecarid}</td>
			<td>{DATA.numseats}</td>
			<td>{DATA.price_listing}</td>
			<td>{DATA.price_negotiate}</td>
			<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{link_edit}" title="">{LANG.edit}</a>&nbsp; <i class="fa fa-trash-o">&nbsp;</i><a href="{link_del}" class="delete" title="">{LANG.delete}</a></td>
		</tr>
	<!-- END: row -->
	</tbody>
	<tfoot>
		<tr>
			<td colspan="8"><i class="fa fa-check-square-o">&nbsp;</i><a href="#" id="checkall">{LANG.select}</a> - <i class="fa fa-square-o">&nbsp;</i> <a href="#" id="uncheckall">{LANG.unselect}</a> - <i class="fa fa-trash-o">&nbsp;</i><a href="#" id="delall">{LANG.del_select}</a></td>
		</tr>
	</tfoot>
</table>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type='text/javascript'>
	$(function() {
		$('#checkall').click(function() {
			$('input:checkbox').each(function() {
				$(this).attr('checked', 'checked');
			});
		});
		$('#uncheckall').click(function() {
			$('input:checkbox').each(function() {
				$(this).removeAttr('checked');
			});
		});
		$('#delall').click(function() {
			if (confirm("{LANG.del_confirm}")) {
				var listall = [];
				$('input.ck:checked').each(function() {
					listall.push($(this).val());
				});
				if (listall.length < 1) {
					alert("{LANG.prounit_del_no_items}");
					return false;
				}
				$.ajax({
					type : 'POST',
					url : '{URL_DEL}',
					data : 'listall=' + listall,
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('a.delete').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.del_confirm}")) {
				var href = $(this).attr('href');
				$.ajax({
					type : 'POST',
					url : href,
					data : '',
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
	});
</script>
<!-- END: data -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="" method="post">
	<input name="savecat" type="hidden" value="1" />
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<td align="right" width="150px"><strong>{LANG.select_producer_name}: </strong></td>
			<td>
				<select class="form-control" name="producerid">
					<option value="0">--------</option>
					<!-- BEGIN: producer -->
					<option value="{PROCUDER.id}"{PROCUDER.sl}>{PROCUDER.title}</option>
					<!-- END: producer -->
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" width="150px"><strong>{LANG.select_typecar_name}: </strong></td>
			<td>
				<select class="form-control" name="typecarid">
					<option value="0">--------</option>
					<!-- BEGIN: typecar -->
					<option value="{TYPECAR.id}"{TYPECAR.sl}>{TYPECAR.title}</option>
					<!-- END: typecar -->
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" width="150px"><strong>{LANG.numseats}: </strong></td>
			<td><input required class="form-control" style="width: 600px" name="numseats" type="text" value="{DATA_POST.numseats}" maxlength="3" /></td>
		</tr>
		<tr>
			<td align="right" width="150px"><strong>{LANG.price_listing}: </strong></td>
			<td><input required class="form-control" style="width: 600px" name="price_listing" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{DATA_POST.price_listing}" maxlength="255" /></td>
		</tr>
		<tr>
			<td align="right" width="150px"><strong>{LANG.price_negotiate}: </strong></td>
			<td><input required class="form-control" style="width: 600px" name="price_negotiate" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{DATA_POST.price_negotiate}" maxlength="255" /></td>
		</tr>
		<tr>
			<td align="right" width="150px"><strong>{LANG.temcar_name}: </strong></td>
			<td><input required class="form-control" style="width: 600px" name="title" type="text" value="{DATA_POST.title}" maxlength="255" /></td>
		</tr>
		<tr>
			<td align="right" width="150px"><strong>{LANG.image}: </strong></td>
			<td>
				<div class="input-group">
					<input class="form-control" style="width: 600px" type="text" name="image" value="{DATA_POST.image}" id="id_no_image_cat" /> <span class="input-group-btn">
							<button class="btn btn-default selectid_image" type="button">
								<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
							</button>
						</span>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right"><strong>{LANG.note}: </strong></td>
			<td><input class="form-control" style="width: 600px" name="note" type="text" value="{DATA_POST.note}" maxlength="255" /></td>
		</tr>
	</table>
	<br>
	<div class="text-center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>
<script>
    $(".selectid_image").click(function() {
        var area = "id_no_image_cat";
        var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
        var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
</script>
<!-- END: main -->