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
                    <select style="width: 100%;" class="form-control" name="school_type">
    					<option value="0">{LANG.chossen_school_type}</option>
                        <!-- BEGIN: school_type -->
    					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
    					<!-- END: school_type -->
				    </select>
                </td>
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
                    <input class="form-control" placeholder="{LANG.from_year}" style="width: 100px;" type="text" value="{from_year}" name="from_year" maxlength="4" />&nbsp;-&nbsp;
                    <input class="form-control" placeholder="{LANG.to_year}" style="width: 100px;" type="text" value="{to_year}" name="to_year" maxlength="4" />&nbsp;
	                <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
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
					<th>{LANG.school_title}</th>
					<!-- BEGIN: year -->
                    <th class="text-center">{YEAR}</th>
                    <!-- END: year -->
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.school_title} </td>
					<!-- BEGIN: year -->
                    <td class="text-center">
                        <!-- BEGIN: allow_change -->
                        <input type="text" name="diemchuan[{VIEW.schoolid}][{YEAR}]" value="{score}" class="form-control" />
                        <!-- END: allow_change -->
                        <!-- BEGIN: not_allow_change -->
                        <strong>{score}</strong>
                        <!-- END: not_allow_change -->
                    </td>
                    <!-- END: year -->
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
        <!-- BEGIN: allow_button_add -->
        <div style="text-align: center"><input class="btn btn-primary" name="save_score" type="submit" value="{LANG.save}" /></div>
        <!-- END: allow_button_add -->
        
	</div>
</form>
<!-- END: main -->