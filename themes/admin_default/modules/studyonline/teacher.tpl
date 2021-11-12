<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.weight}</th>
                    <th style="width:80px">{LANG.avatar}</th>
					<th>{LANG.teacher_name}</th>
                    <th>{LANG.list_subject_study}</th>
					<th>{LANG.updatetime}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
						<select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
    						<!-- BEGIN: weight_loop -->
    							<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
    						<!-- END: weight_loop -->
    					</select>
    				</td>
                    <td><!-- BEGIN: avatar --><img src="{avatar}" width="40px" /><!-- END: avatar --></td>
					<td> {VIEW.title} </td>
                    <td> {VIEW.subject_title} </td>
					<td> {VIEW.updatetime} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
        <div class="text-center"><a class="btn btn-primary" href="{addnew_teacher}">{LANG.addnew_teacher}</a></div>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: addnew -->
<div class="row">
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    	<input type="hidden" name="id" value="{ROW.id}" />
        <div class="col-sm-24 col-md-18">
        	<div class="table-responsive">
        		<table class="table table-striped table-bordered table-hover">
        			<tbody>
        				<tr>
        					<td> {LANG.teacher_name} </td>
        					<td><input class="form-control w300" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
        				</tr>
        				<tr>
        					<td> {LANG.avatar} </td>
        					<td><input class="form-control w300" type="text" name="avatar" value="{ROW.avatar}" id="id_avatar" />&nbsp;<button type="button" class="btn btn-info" id="img_avatar"><i class="fa fa-folder-open-o">&nbsp;</i> Browse server </button></td>
        				</tr>
                        <tr>
        					<td> {LANG.facebooklink} </td>
        					<td><input class="form-control w300" type="text" name="facebooklink" value="{ROW.facebooklink}" /></td>
        				</tr>
        				<tr>
        					<td> {LANG.teacher_address} </td>
        					<td><input class="form-control w300" type="text" name="address" value="{ROW.address}" /></td>
        				</tr>
                        <tr>
        					<td> {LANG.teacher_phone} </td>
        					<td><input class="form-control w300" type="text" name="mobile" value="{ROW.mobile}" /></td>
        				</tr>
        				<tr>
        					<td> {LANG.teacher_email} </td>
        					<td><input class="form-control w300" type="text" name="email" value="{ROW.email}" /></td>
        				</tr>
                        <tr>
        					<td> {LANG.teacher_infotext} </td>
        					<td>{ROW.infotext}</td>
        				</tr>
        			</tbody>
        		</table>
        	</div>
            <div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
        </div>
        <div class="col-sm-24 col-md-6">
            <table class="table table-striped table-bordered table-hover">
        			<tbody>
                        <tr>
        					<td><input placeholder="{LANG.alias}" class="form-control" type="text" name="alias" value="{ROW.alias}" id="id_alias" />&nbsp;<i class="fa fa-refresh fa-lg icon-pointer" onclick="nv_get_alias('id_alias');">&nbsp;</i></td>
        				</tr>
                        <tr>
        					<td>
                                <strong>{LANG.list_subject_study}</strong><br />
                                <!-- BEGIN: list_subject -->
                                <label><input class="form-control" name="subjectlist[]" type="checkbox" value="{SUBJECT.id}"{SUBJECT.ck} />{SUBJECT.title}</label>&nbsp;
                                <!-- END: list_subject -->
                            </td>
        				</tr>
        				<tr>
        					<td>
                                <select class="form-control" name="status">
                					<option value=""> {LANG.status} </option>
                					<!-- BEGIN: select_status -->
                					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                					<!-- END: select_status -->
                				</select>
                            </td>
        				</tr>
        				<tr>
        					<td><textarea placeholder="{LANG.description}" class="form-control" style="width: 98%; height:100px;" cols="75" rows="5" name="description">{ROW.description}</textarea></td>
        				</tr>
        			</tbody>
        		</table>
        </div>
    </form>
</div>
<script type="text/javascript">
//<![CDATA[
	function nv_get_alias(id) {
		var title = strip_tags( $("[name='title']").val() );
		if (title != '') {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=teacher&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
				$("#"+id).val( strip_tags( res ) );
			});
		}
		return false;
	}
	$("#img_avatar").click(function() {
		var area = "id_avatar";
		var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_NAME}/teacher";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
//]]>
</script>

<!-- BEGIN: auto_get_alias -->
<script type="text/javascript">
//<![CDATA[
	$("[name='title']").change(function() {
		nv_get_alias('id_alias');
	});
//]]>
</script>
<!-- END: auto_get_alias -->
<!-- END: addnew -->
<script type="text/javascript">
    function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=teacher&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=teacher';
			return;
		});
		return;
	}
</script>
<!-- END: main -->