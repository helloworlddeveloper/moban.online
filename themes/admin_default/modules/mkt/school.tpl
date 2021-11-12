<!-- BEGIN: main -->
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={CONFIG.Google_Maps_API_Key}" type="text/javascript"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/googlemap.js"></script>
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<strong>{LANG.search_title}</strong>&nbsp;<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
    <input class="btn btn-primary" type="submit" value="{LANG.search}" />
    <!-- BEGIN: allow_link_add -->
	<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />&nbsp;<a href="{addschool}" class="btn btn-primary">{LANG.addschool}</a>
    <!-- END: allow_link_add -->
    <!-- BEGIN: allow_link_viewschool_view -->
    &nbsp;<a href="{viewschoolonmap}" class="btn btn-primary">{LANG.viewschoolonmap}</a>
    <!-- END: allow_link_viewschool_view -->
</form>
<br />
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
                    <th>{LANG.code}</th>
					<th>{LANG.school_title}</th>
                    <th>{LANG.num_student}</th>
					<th>{LANG.score_money}</th>
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
                    <td> {VIEW.code} </td>
					<td> {VIEW.school_title} </td>
                    <td> {VIEW.num_student} </td>	
					<td> {VIEW.score_money} </td>
					<td> {VIEW.edit_time} </td>
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
    <!-- BEGIN: add_row -->
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&action={action}" method="post">
    	<input type="hidden" name="schoolid" value="{ROW.schoolid}" />
    	<div class="table-responsive">
    		<table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td style="width:100px"> {LANG.school_title} <span class="red">(*)</span></td>
    					<td><input style="width: 100%;" class="form-control" type="text" name="school_title" value="{ROW.school_title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
                        <td style="width:120px">{LANG.code_school}</td>
                        <td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="code_school" id="code_school" value="{ROW.code}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.alias} </td>
    					<td><input style="width: 90%;" class="form-control" type="text" name="alias" value="{ROW.school_alias}" id="id_alias" />&nbsp;<i class="fa fa-refresh fa-lg icon-pointer" onclick="nv_get_alias('id_alias');">&nbsp;</i></td>
                        <td> {LANG.schooltype} </td>
    					<td>
                            <select style="width: 100%;" class="form-control" name="schooltype">
            					<!-- BEGIN: schooltype -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: schooltype -->
        				    </select>
                        </td>
    				</tr>
                    <tr class="schooltype_num_student">
                        <td>{LANG.num_student}</td>
                        <td colspan="3" style="width:auto;">
                            <table id="schooltype_1" class="table table-bordered">
                                <tr>
                                    <td title="{LANG.schooltype_1_1}"><input placeholder="{LANG.schooltype_1_1}" class="form-control" type="text" name="num_student_1_1" value="{ROW.num_student_1}" /></td>
                                    <td title="{LANG.schooltype_1_2}"><input placeholder="{LANG.schooltype_1_2}" class="form-control" type="text" name="num_student_1_2" value="{ROW.num_student_2}" /></td>
                                    <td title="{LANG.schooltype_1_3}"><input placeholder="{LANG.schooltype_1_3}" class="form-control" type="text" name="num_student_1_3" value="{ROW.num_student_3}" /></td>
                                    <td title="{LANG.schooltype_1_4}"><input placeholder="{LANG.schooltype_1_4}" class="form-control" type="text" name="num_student_1_4" value="{ROW.num_student_4}" /></td>
                                    <td title="{LANG.schooltype_1_5}"><input placeholder="{LANG.schooltype_1_5}" class="form-control" type="text" name="num_student_1_5" value="{ROW.num_student_5}" /></td>
                                </tr>
                            </table>
                            <table id="schooltype_2" class="table table-bordered">
                                <tr>
                                    <td title="{LANG.schooltype_2_1}"><input placeholder="{LANG.schooltype_2_1}" class="form-control" type="text" name="num_student_2_1" value="{ROW.num_student_1}" /></td>
                                    <td title="{LANG.schooltype_2_2}"><input placeholder="{LANG.schooltype_2_2}" class="form-control" type="text" name="num_student_2_2" value="{ROW.num_student_2}" /></td>
                                    <td title="{LANG.schooltype_2_3}"><input placeholder="{LANG.schooltype_2_3}" class="form-control" type="text" name="num_student_2_3" value="{ROW.num_student_3}" /></td>
                                    <td title="{LANG.schooltype_2_4}"><input placeholder="{LANG.schooltype_2_4}" class="form-control" type="text" name="num_student_2_4" value="{ROW.num_student_4}" /></td>
                                </tr>
                            </table>
                            <table id="schooltype_3" class="table table-bordered">
                                <tr>
                                    <td title="{LANG.schooltype_3_1}"><input placeholder="{LANG.schooltype_3_1}" class="form-control" type="text" name="num_student_3_1" value="{ROW.num_student_1}" /></td>
                                    <td title="{LANG.schooltype_3_2}"><input placeholder="{LANG.schooltype_3_2}" class="form-control" type="text" name="num_student_3_2" value="{ROW.num_student_2}" /></td>
                                    <td title="{LANG.schooltype_3_3}"><input placeholder="{LANG.schooltype_3_3}" class="form-control" type="text" name="num_student_3_3" value="{ROW.num_student_3}" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.address} <span class="red">(*)</span></td>
                        <td><input style="width: 100%;" class="form-control" type="text" name="address" value="{ROW.address}" /></td>
                        <td>{LANG.note_num_student}</td>
                        <td><input class="form-control" style="width: 100%;" type="text" name="note_num_student" placeholder="{LANG.note_num_student}" value="{ROW.note_num_student}" /></td>
                    </tr>
                    <tr>
                        <td rowspan="4" colspan="2">
                            <textarea class="form-control" placeholder="{LANG.hometext_school}" style="width: 100%; height:120px;" cols="75" rows="5" name="hometext">{ROW.hometext}</textarea>
                        </td>
                        <td>{LANG.district_select}</td>
                        <td id="district_data">
                            <select style="width: 100%;" class="form-control" name="districtid">
                                <option value="0">---------</option>
            					<!-- BEGIN: district_select -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: district_select -->
        				    </select>
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.province_select}</td>
                        <td>
                            <select style="width: 100%;" onchange="nv_get_district(this.value, 0)" class="form-control" name="provinceid">
            					<!-- BEGIN: province_select -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: province_select -->
        				    </select>
                        </td>
                    </tr>
                    <tr>
    					<td> {LANG.score_money} </td>
    					<td>
                            <select style="width: 100%;" class="form-control" name="score_money">
            					<!-- BEGIN: score_money -->
            					<option value="{OPTION_MONEY.key}" {OPTION_MONEY.selected}>{OPTION_MONEY.title}</option>
            					<!-- END: score_money -->
        				    </select>
                        </td>
    				</tr>
                    <tr>
    					<td> {LANG.score_study} </td>
    					<td>
                            <select style="width: 100%;" class="form-control" name="score_study">
            					<!-- BEGIN: score_study -->
            					<option value="{OPTION_STUDY.key}" {OPTION_STUDY.selected}>{OPTION_STUDY.title}</option>
            					<!-- END: score_study -->
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
                        <td> {LANG.status} </td>
    					<td>
                            <select style="width: 100%;" class="form-control" name="status">
            					<!-- BEGIN: select_status -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: select_status -->
        				    </select>
                        </td>
    				</tr>
                    <tr>
                        <td style="width:50%" colspan="4">
                            <input type="hidden" id="gmap_lat" name="gmap_lat" value="{ROW.gmap_lat}" />
                            <input type="hidden" id="gmap_lng" name="gmap_lng" value="{ROW.gmap_lng}" />
                            <div id="googlemap" style="width: 100%; height: 360px">
                        </td>
    				</tr>
    			</tbody>
    		</table>
    	</div>
    	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
    </form>
    
    <script type="text/javascript">
        googlemapload({MAPS_CONFIG.gmap_lat}, {MAPS_CONFIG.gmap_lng}, {MAPS_CONFIG.gmap_z});
    //<![CDATA[
        $('.schooltype_num_student,#schooltype_1,#schooltype_2,#schooltype_3').hide();
        function show_schooltype(){
            $('.schooltype_num_student,#schooltype_1,#schooltype_2,#schooltype_3').hide();
            $('.schooltype_num_student,#schooltype_' +$('select[name=schooltype]').val()).show();
        }
        show_schooltype();
        $('select[name=schooltype]').change(function(){
            show_schooltype();
        })
        nv_get_district('{ROW.provinceid}', '{ROW.districtid}');
        function nv_get_district(provinceid, districtid) {
            if( provinceid == 0 ){
                provinceid = $('select[name=provinceid]').val();
            }
    		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=school&nocache=' + new Date().getTime(), 'loaddistrict=1&provinceid=' + provinceid + '&districtid=' + districtid, function(res) {
    			$("#district_data").html( res );
    		});
    	}
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