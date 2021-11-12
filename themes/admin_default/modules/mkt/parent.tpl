<!-- BEGIN: main -->
<script type="text/javascript">
    function nv_get_district(provinceid, districtid) {
        if( provinceid == 0 ){
            provinceid = $('select[name=provinceid]').val();
        }
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'loaddistrict=1&provinceid=' + provinceid + '&districtid=' + districtid, function(res) {
			$("#district_data").html( res );
		});
	}
</script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={CONFIG.Google_Maps_API_Key}" type="text/javascript"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/googlemap.js"></script>
<!-- BEGIN: view -->
<script type="text/javascript">
    <!-- BEGIN: loaddistrict -->
    nv_get_district('{provinceid}', '{districtid}');
    <!-- END: loaddistrict -->
</script>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    
    <table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
                <td>
                    <select style="width: 100%;" onchange="nv_get_district(this.value, 0)" class="form-control" name="provinceid">
    					<option value="0">{LANG.province_search}</option>
                        <!-- BEGIN: province_select -->
    					<option value="{OPTION.id}" {OPTION.selected}>{OPTION.title}</option>
    					<!-- END: province_select -->
				    </select>
                </td>
                <td id="district_data">
                    <select style="width: 100%;" class="form-control" name="districtid">
                        <option value="0">{LANG.district_search}</option>
				    </select>
                </td>
                <td>
                    <input class="form-control" placeholder="{LANG.search_title}" style="width: 300px;" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
                    <!-- BEGIN: allow_link_add -->
	                <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />&nbsp;<a href="{addschool}" class="btn btn-primary">{LANG.addparent}</a>
                    <!-- END: allow_link_add -->
                </td>
            </tr>
        </tbody>
    </table>        
</form>
<br />
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
                    <th>{LANG.district}</th>
                    <th>{LANG.jobs}</th>
					<th>{LANG.parent_name}</th>
					<th>{LANG.email}</th>
                    <th>{LANG.mobile}</th>
					<th>{LANG.edit_time}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
                    <td> {VIEW.district_name} </td>
                    <td> {VIEW.jobs_name} </td>
					<td> {VIEW.parent_name} </td>	
					<td> {VIEW.email} </td>
                    <td> {VIEW.mobile} </td>
					<td> {VIEW.edit_time} </td>
					<td> {VIEW.status} </td>
                    <td class="text-center">
                        <!-- BEGIN: allow_edit -->
                        <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a>
                        <!-- END: allow_edit -->
                        <!-- BEGIN: allow_del -->
                         - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                        <!-- END: allow_del -->
                        <!-- BEGIN: allow_eventcontent -->
                         - <i class="fa fa-history fa-lg">&nbsp;</i><a href="{VIEW.addevent}">{LANG.addevent}</a>
                        <!-- END: allow_eventcontent -->
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: allow_add -->
    <!-- BEGIN: add_row -->
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->
    <link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
    <link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
    <link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
    
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&action={action}" method="post">
    	<input type="hidden" name="parentid" value="{ROW.parentid}" />
    	<div class="table-responsive">
    		<table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td style="width:100px"> {LANG.parent_name} <span class="red">(*)</span></td>
    					<td><input style="width: 100%;" class="form-control" type="text" name="parent_name" value="{ROW.parent_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
                        <td style="width:120px">{LANG.birthday}</td>
                        <td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="birthday" id="birthday" value="{ROW.birthday}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.mobile} </td>
    					<td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="mobile" id="mobile" value="{ROW.mobile}" /></td>
                        <td> {LANG.numberphone} </td>
    					<td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="numberphone" id="numberphone" value="{ROW.numberphone}" /></td>
    				</tr>
                    <tr>
                        <td> {LANG.email} </td>
    					<td><input style="width: 100%;" class="form-control" type="text" name="email" id="email" value="{ROW.email}" /></td>
                        <td>{LANG.address} <span class="red">(*)</span></td>
                        <td><input style="width: 100%;" class="form-control" type="text" name="address" value="{ROW.address}" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.province_select}</td>
                        <td>
                            <select style="width: 100%;" onchange="nv_get_district(this.value, 0)" class="form-control" name="provinceid">
            					<!-- BEGIN: province_select -->
            					<option value="{OPTION.id}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: province_select -->
        				    </select>
                        </td>
                        <td>{LANG.district_select}</td>
                        <td id="district_data">
                            <select style="width: 100%;" class="form-control" name="districtid">
                                <option value="0">---------</option>
        				    </select>
                        </td>
                    </tr>
                    <tr>
                        <td> {LANG.jobs} </td>
    					<td>
                            <select style="width: 100%;" class="form-control" name="jobs">
                                <option value="0">---------</option>
            					<!-- BEGIN: jobs_select -->
            					<option value="{JOBS.id}" {JOBS.selected}>{JOBS.title}</option>
            					<!-- END: jobs_select -->
        				    </select>
                        </td>
    					<td> {LANG.income} </td>
    					<td>
                            <select style="width: 100%;" class="form-control" name="income">
            					<!-- BEGIN: income -->
            					<option value="{OPTION_MONEY.key}" {OPTION_MONEY.selected}>{OPTION_MONEY.title}</option>
            					<!-- END: income -->
        				    </select>
                        </td>
    				</tr>
                    <tr>
                        <td><strong>{LANG.maps_school}</strong></td>
                        <td>
                            <input class="form-control" type="text" id="address" placeholder="{LANG.maps_search}" style='width:60%' />
                            <input type="button"  class="btn btn-primary" onclick="showAddress(document.getElementById('address').value); return false" value="Search" />
                            <i>{LANG.maps_search_note}</i>
                        </td>
                        <td>{LANG.from_by}</td>
                        <td>
                            <select style="width: 100%;" class="form-control" name="from_by">
                                <option value="0">-----</option>
            					<!-- BEGIN: from_select -->
            					<option value="{FROM.id}" {FROM.selected}>{FROM.title}</option>
            					<!-- END: from_select -->
        				    </select>
                        </td>
    				</tr>
                    <tr>
                        <td> {LANG.status} </td>
    					<td colspan="3">
                            <select style="width: 100%;" class="form-control" name="status">
            					<!-- BEGIN: select_status -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: select_status -->
        				    </select>
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="3"><div id="googlemap" style="width: 100%; height: 360px"></div></td>
                        <td>
                            <input type="hidden" id="gmap_lat" name="gmap_lat" value="{ROW.gmap_lat}" />
                            <input type="hidden" id="gmap_lng" name="gmap_lng" value="{ROW.gmap_lng}" />
                            <input class="btn btn-primary" name="save_and_add_student" type="submit" value="{LANG.save_and_add_student}" />
                            <input class="btn btn-primary" name="save_only" type="submit" value="{LANG.save_only}" />
                        </td>
    				</tr>
    			</tbody>
    		</table>
    	</div>
    </form>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
    <script type="text/javascript">
        $("#birthday").datepicker({
    		showOn : "focus",
    		dateFormat : "dd/mm/yy",
    		changeMonth : true,
    		changeYear : true,
    		showOtherMonths : true,
    		buttonImage : nv_siteroot + "images/calendar.gif",
    		buttonImageOnly : true
    	});
        nv_get_district('{ROW.provinceid}', '{ROW.districtid}');
        googlemapload({MAPS_CONFIG.gmap_lat}, {MAPS_CONFIG.gmap_lng}, {MAPS_CONFIG.gmap_z});
    //<![CDATA[
    	function nv_get_alias(id) {
    		var title = strip_tags( $("[name='school_title']").val() );
    		if (title != '') {
    			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=school&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
    				$("#"+id).val( strip_tags( res ) );
    			});
    		}
    		return false;
    	}
        function nv_get_code(id) {
    		var title = strip_tags( $("[name='school_title']").val() );
    		if (title != '') {
    			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=school&nocache=' + new Date().getTime(), 'get_code=' + encodeURIComponent(title), function(res) {
    				$("#"+id).val( strip_tags( res ) );
    			});
    		}
    		return false;
    	}
    	function nv_change_weight(id) {
    		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
    		var new_vid = $('#id_weight_' + id).val();
    		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=school&nocache=' + new Date().getTime(), 'ajax_action=1&schoolid=' + id + '&new_vid=' + new_vid, function(res) {
    			var r_split = res.split('_');
    			if (r_split[0] != 'OK') {
    				alert(nv_is_change_act_confirm[2]);
    			}
    			clearTimeout(nv_timer);
    			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=school';
    			return;
    		});
    		return;
    	}
        $("input[name='address']").change(function() {
    		var address = $("input[name='address']").val();
            $('#address').val( address );
            nv_search_gmaps(address)
    	});
        function nv_search_gmaps(address) {
            if( address != '' ){
                showAddress( address );
            }
        }
    //]]>
    </script>
    
    <!-- BEGIN: auto_get_alias -->
    <script type="text/javascript">
    //<![CDATA[
    	$("[name='school_title']").change(function() {
    		nv_get_alias('id_alias');
            nv_get_code('code_school');
    	});
    //]]>
    </script>
    
    <!-- END: auto_get_alias -->
    <!-- END: add_row -->
<!-- END: allow_add -->
<!-- END: main -->