<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
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
	var nv_teachers_delete_confirm = '{LANG.teachers_delete_confirm}';
	var nv_teachers_change_status_confirm = '{LANG.teachers_change_status_confirm}';
	var nv_error_unknow = '{LANG.error_unknow}';
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{module_file}/js/gentest.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{module_file}/js/popup.js"></script>
<div class="form-inline table-responsive">
    <table class="table table-striped table-bordered table-hover">
    	<tbody>
    		<tr>
                <td>
                    <select style="width: 100%;" class="form-control" name="schoolid">
    					<option value="0">{LANG.school_search}</option>
                        <!-- BEGIN: school_select -->
    					<option value="{OPTION.id}" {OPTION.selected}>{OPTION.title}</option>
    					<!-- END: school_select -->
    			    </select>
                </td>
                <td>
                    <select style="width: 100%;" class="form-control" name="status">
    					<option value="0">{LANG.status_search}</option>
                        <!-- BEGIN: status_select -->
    					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
    					<!-- END: status_select -->
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
                    <input class="form-control" style="width:230px;" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_enterkey}"/>
                    <input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
                    <!-- BEGIN: addstudent -->
                    <input class="btn btn-primary" type="button" value="{LANG.add_student}" onclick="window.location='{URL_ADD}';"/>
                    <!-- END: addstudent -->
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    <!-- BEGIN: loaddistrict -->
    nv_get_district('{provinceid}', '{districtid}');
    <!-- END: loaddistrict -->
$(document).ready(function(){
    $('#search input[type=text]').keyup(function(e){
        if(e.keyCode == 13)
        {
            search_data();
        }
    });
	$('input[name=do]').click(function(){
		search_data();
	});
    function search_data(){
        var f_q = $('input[name=q]').val();
		var provinceid = $('select[name=provinceid]').val();
        var districtid = $('select[name=districtid]').val();
        var schoolid = $('select[name=schoolid]').val();
        var status = $('select[name=status]').val();
		if ( ( f_q != '{LANG.filter_enterkey}' && f_q != '' ) || provinceid != 0 || districtid == '' || schoolid == 0 || status > 0 )
		{
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&provinceid=' + provinceid + '&districtid=' + districtid + '&schoolid=' + schoolid + '&status=' + status;	
		}
		else
		{
			alert ('{LANG.filter_err_submit}');
		}
    }
});
</script>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.student_code}</th>
					<th>{LANG.full_name}</th>
					<th>{LANG.birthday}</th>
                    <th>{LANG.school_title}</th>
                    <th>{LANG.district_title}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
            <!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td colspan="8">{GENERATE_PAGE}</td>
				</tr>
			</tfoot>
            <!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.code} </td>
					<td> {VIEW.student_name}</td>
					<td> {VIEW.birthday} </td>
                    <td> {VIEW.school_title} </td>
                    <td> {VIEW.district_title} </td>
					<td> 
                        <!-- BEGIN: change_status -->
                        <input type="text" class="form-control date_change" value="{VIEW.thoigian}" placeholder="Chọn ngày" name="date_change{VIEW.studentid}" style="width:100px" />
                        <select class="form-control" name="status" id="status{VIEW.studentid}" onchange="nv_change_student_status({VIEW.studentid});">
                            <!-- BEGIN: loop -->
                            <option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
                            <!-- END: loop -->
                        </select>
                        <!-- END: change_status -->
                        <!-- BEGIN: no_change_status -->
                        {VIEW.status_title} 
                        <!-- END: no_change_status -->
                    </td>
					<td class="text-center"><i class="fa fa-eye fa-lg"></i></i> <a href="{VIEW.link_info_student}">{LANG.info_student}</a><!-- BEGIN: edit -->&nbsp;-&nbsp;<i class="fa fa-history fa-lg"></i></i> <a href="{VIEW.link_history}">{LANG.history_student}</a>&nbsp;-&nbsp;<i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a><!-- END: edit --><!-- BEGIN: editavatar -->&nbsp;-&nbsp;<i class="fa fa-user fa-lg">&nbsp;</i> <a href="{VIEW.link_editavatar}">{LANG.editavatar}</a><!-- END: editavatar --></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $(".date_change").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
</script>
<!-- END: main -->
