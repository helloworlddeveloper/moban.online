<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.eventtype_name}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
                        <!-- BEGIN: no_sort_weight -->
                            <div class="text-center"><strong>{VIEW.weight}</strong></div>
                        <!-- END: no_sort_weight -->
                        <!-- BEGIN: sort_weight -->
    						<select class="form-control" id="id_weight_{VIEW.eventtype_id}" onchange="nv_change_weight('{VIEW.eventtype_id}');">
    						<!-- BEGIN: weight_loop -->
    							<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
    						<!-- END: weight_loop -->
                            </select>
                        <!-- END: sort_weight -->
				    </td>
					<td style="color:{VIEW.color}"> {VIEW.eventtype_name} </td>
					<td> {VIEW.status} </td>
					<td class="text-center">
                        <!-- BEGIN: allow_edit -->
                        <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a>
                        <!-- END: allow_edit -->
                        <!-- BEGIN: allow_del -->
                         - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                        <!-- END: allow_del -->
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: allow_add -->
    <script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/colorpicker.js"></script>
    <link rel="stylesheet" href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/colorpicker.css" type="text/css" />
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->
    <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    	<input type="hidden" name="eventtype_id" value="{ROW.eventtype_id}" />
    	<div class="table-responsive">
    		<table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td> {LANG.eventtype_name} </td>
    					<td><input class="form-control" type="text" name="eventtype_name" value="{ROW.eventtype_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.status} </td>
    					<td><select class="form-control" name="status">
    					<option value=""> --- </option>
    					<!-- BEGIN: select_status -->
    					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
    					<!-- END: select_status -->
    				</select></td>
    				</tr>
                    <tr>
                        <td>{LANG.color_event}</td>
                        <td><input class="form-control mausac" type="text" value="{ROW.color}" name="color" /></td>
                    </tr>
    			</tbody>
    		</table>
    	</div>
    	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
    </form>
<!-- END: allow_add -->
<script type="text/javascript">
    $('.mausac').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val('#' + hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
//<![CDATA[
	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'ajax_action=1&eventtype_id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
			return;
		});
		return;
	}
//]]>
</script>
<!-- END: main -->