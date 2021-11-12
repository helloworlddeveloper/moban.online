<!-- BEGIN: main -->
<!-- BEGIN: view -->
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-24 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_title}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
				</div>
			</div>
		</div>
	</form>
</div>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w100">{LANG.weight}</th>
					<th>{LANG.car_number_plate}</th>
					<th>{LANG.mobilephone}</th>
					<th>{LANG.number_seats}</th>
					<th>{LANG.note}</th>
					<th class="w200 text-center">{LANG.active}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
							<!-- BEGIN: weight_loop -->
							<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
							<!-- END: weight_loop -->
					</select></td>
					<td>{VIEW.car_number_plate}</td>
					<td>{VIEW.mobilephone}</td>
					<td>{VIEW.number_seats}</td>
					<td>{VIEW.note}</td>
					<td class="text-center"><input type="checkbox" name="active" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" />
					<!-- BEGIN: showactive -->
					{LANG.active_success}
					<!-- END: showactive -->
					<!-- BEGIN: showdanger -->
					{LANG.active_danger}
					<!-- END: showdanger -->
					</td>
					<td class="text-center"><a href="{VIEW.link_edit}#edit" data-toggle="tooltip" data-original-title="{LANG.edit}"><i class="fa fa-edit fa-lg">&nbsp;</i></a> - <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<div class="col-sm-24 col-md-8">
					<label class="control-label"><strong>{LANG.car_number_plate}</strong> <span class="red">(*)</span></label> <input placeholder="74K-12345" class="form-control" type="text" name="car_number_plate" value="{ROW.car_number_plate}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
				</div>
				<div class="col-sm-24 col-md-8">
					<label class="control-label"><strong>{LANG.number_seats}</strong> <span class="red">(*)</span></label> <input class="form-control" type="text" name="number_seats" value="{ROW.number_seats}" pattern="^[0-9]*$" oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" required="required" />
				</div>
				<div class="col-sm-24 col-md-8">
					<label class="control-label"><strong>{LANG.mobilephone}</strong></label> <input class="form-control" type="text" name="mobilephone" value="{ROW.mobilephone}" pattern="^[0-9]*$" oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" required="required" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-19 col-md-24">
					<label class="control-label"><strong>{LANG.note}</strong></label>
					<textarea class="form-control" style="height: 100px;" cols="75" rows="5" name="note">{ROW.note}</textarea>
				</div>
			</div>
			<div class="form-group text-center">
				<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
//<![CDATA[
	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manage_vehicle&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manage_vehicle';
			return;
		});
		return;
	}


	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manage_vehicle&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
				var r_split = res.split('_');
				if (r_split[0] != 'OK') {
					alert(nv_is_change_act_confirm[2]);
				}
			});
		}
		else{
			$('#change_status_' + id).prop('checked', new_status ? false : true );
		}
		return;
	}


//]]>
</script>
<!-- END: main -->