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
                    <input class="form-control" placeholder="{LANG.num_year}" style="width: 180px;" type="text" value="{num_year}" name="num_year" maxlength="2" />&nbsp;-&nbsp;
                    <input class="form-control" placeholder="{LANG.dudoan_year}" style="width: 150px;" type="text" value="{dudoan_year}" name="dudoan_year" maxlength="4" />&nbsp;
	                <input class="btn btn-primary" type="submit" value="{LANG.trongso_submit}" />
                </td>
            </tr>
        </tbody>
    </table>        
</form>
<br />
<form onsubmit="return submit_data();" class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input class="form-control" type="hidden" value="{num_year}" name="num_year" maxlength="2" />&nbsp;-&nbsp;
    <input class="form-control" type="hidden" value="{dudoan_year}" name="dudoan_year" maxlength="4" />&nbsp;
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.school_title}</th>
					<!-- BEGIN: year -->
                    <th class="text-center">{YEAR}</th>
                    <!-- END: year -->
				</tr>
                <tr>
                    <th>{LANG.trongso_percent}</th>
					<!-- BEGIN: year_trong_so -->
                    <th class="text-center">
                        <input class="form-control" placeholder="{LANG.trongso_nam} {YEAR}" style="width: 100%;" type="text" value="0" name="trongso[{trongso}]" data_index="{trongso}" id="trongso_{trongso}" maxlength="5" />
                    </th>
                    <!-- END: year_trong_so -->
                    <th>
                        <input class="btn btn-primary" type="submit" name="save_score" value="Dự đoán điểm chuẩn" />
                    </th>
                </tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.school_title} </td>
					<!-- BEGIN: year -->
                    <td class="text-center">
                        <input type="hidden" name="diemchuan" data_schoolid="{VIEW.schoolid}" data_year="{YEAR}" value="{score}" class="form-control" />
                        <strong>{score}</strong>
                    </td>
                    <!-- END: year -->
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
    function reset_trongso( length_strongso, index_batdau ){
        for(var batdau=index_batdau; batdau<length_strongso; batdau++){
            $('#trongso_' + batdau).val('');
    	}
    }
    function set_trongso( length_strongso, index_batdau, trongso ){
        for(var batdau=index_batdau; batdau<length_strongso; batdau++){
            $('#trongso_' + batdau).val(trongso);
    	}
    }    
    $('input[name^="trongso"]').blur(function() {
        var sumOfValues = 0;
        
        var index = $(this).attr('data_index');
        var index_batdau = parseInt(parseInt(index) + 1);
        var length_trongso = $('input[name^="trongso"]').length;
        reset_trongso( length_trongso, index_batdau );
        
        for(var i=0; i<index_batdau; i++){
            var trongso_input = $('#trongso_' + i).val();
    		if( trongso_input > 0 ){
    			sumOfValues += Number( trongso_input );
    		}
    	}
        var trong_so_con_lai = 100-sumOfValues;
        var trong_so_moi = trong_so_con_lai / (parseInt(length_trongso-index-1));
        trong_so_moi = Number(Math.round(trong_so_moi+'e'+2)+'e-'+2);
        
        set_trongso( length_trongso, index_batdau, trong_so_moi ); 
    });
    function submit_data(){
        var sumOfValues = 0;
        var length_trongso = $('input[name^="trongso"]').length;
        
        for(var i=0; i<length_trongso; i++){
            var trongso_input = $('#trongso_' + i).val();
    		if( trongso_input > 0 ){
    			sumOfValues += Number( trongso_input );
    		}
    	}
        if( sumOfValues > 100 ){
            alert('Tổng trọng số không thể lớn hơn 100%');
            return false
        }else{
            return true;
        }   
    }
</script>
<!-- END: main -->