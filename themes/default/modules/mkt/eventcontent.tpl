<!-- BEGIN: main -->
<form class="form-inline" method="get" onsubmit="return submit_search()">
    <table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
                <td>
                    <input class="form-control" placeholder="{LANG.input_mobile}" type="text" value="{mobile}" name="input_mobile" maxlength="255" />&nbsp;
	                <input class="btn btn-primary" type="submit" value="{LANG.search}" />
                </td>
            </tr>
        </tbody>
    </table> 
</form>
<div id="load_data"></div>
<script type="text/javascript">
    function submit_search(){
        var input_mobile = $('input[name=input_mobile]').val();
        if( input_mobile == '' ){
            alert('Bạn cần nhập sđt hoặc ID !');
        }else{
            $('#load_data').html('<center>{LANG.searching}&nbsp;<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" /></center>');
            $.post(nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'search=1&inputmobile=' + input_mobile +'&num=' + nv_randomPassword( 8 ), function(res) {
        		$('#load_data').html(res);
        	});
            return false;
        }
        return false;
    }
    <!-- BEGIN: search -->
    $.post(nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'search=1&id={id}&eventid={eventid}&num=' + nv_randomPassword( 8 ), function(res) {
        $('#load_data').html(res);
    });
    <!-- END: search -->
</script>

<!-- END: main -->
<!-- BEGIN: data_show -->
    <table class="table table-striped table-bordered table-hover">
    	<tbody>
    		<tr>
                <td>
                    <strong>{LANG.full_name}</strong>
                </td>
                <td>{ROW.full_name}</td>
    			<td> <strong>{LANG.birthday}</strong> </td>
    			<td>{ROW.birthday}</td>
    		</tr>
    		<tr>
                <td> <strong>{LANG.mobile}</strong> </td>
    			<td>{ROW.mobile}</td>
                <td> <strong>{LANG.email}</strong></td>
    			<td>{ROW.email}</td>
    		</tr>
    		<tr>
    			<td> <strong>{LANG.address}</strong> </td>
    			<td>{ROW.address}</td>
                <td> <strong>Người giới thiệu</strong> </td>
                <td>{DATA_REFER.fullname} - {DATA_REFER.mobile}</td>
    		</tr>
    	</tbody>
    </table>
    <script type="text/javascript">
        function save_history(customerid, eventid){
            var note = $('textarea[name=note]').val();
            var eventtype = $('select[name=eventtype]').val();
            if( note == '' ){
                alert('Bạn chưa nhập nội dung ghi chú');
                $('textarea[name=note]').focus();
                return;
            }else if( eventtype == 0 ){
                alert('Bạn chưa chọn dữ liệu loại sự kiện');
                return;
            }else{
                var nv_timer = nv_settimeout_disable('btn_save_history', 5000);
                
                var measureid = $('select[name=measureid]').val();
                var status_action = $('select[name=status_action]').val();
                var remkt_time = $('input[name=remkt_time]').val();
                $.post(nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'save=1&customerid=' + customerid + '&eventid=' + eventid + '&note=' + note + '&eventtype='+eventtype+'&measureid='+measureid+ '&status_action=' + status_action +'&remkt_time=' + remkt_time + '&num=' + nv_randomPassword( 8 ), function(res) {
            		if( res == "OK"){
            		    window.location.href = window.location.href;
            		}else{
            		  alert('Lỗi không được lưu nội dung');
            		}
                    clearTimeout(nv_timer);
            	});
            }
        }
    </script>
    <div class="table-responsive">
        <form class="form-inline" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <table class="table table-striped table-bordered table-hover">
                <caption>Ghi thêm lịch sử chăm sóc</caption>
        		<tbody>
        			<tr>
        				<td>{LANG.content_history}</td>
        				<td><textarea class="form-control" name="note" style="width:100%;height:60px">{CONTENT_NOTE}</textarea></td>
        			</tr>
                    <tr>
                        <td>{LANG.eventtype_name}</td>
                        <td>
                            <select class="form-control" name="eventtype">
                                <option value="0">--------</option>
                            <!-- BEGIN: eventtype -->
                                <option value="{EVENT.id}">{EVENT.title}</option>
                            <!-- END: eventtype --> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.measure_select}</td>
                        <td>
                            <select class="form-control" name="measureid">
                            <!-- BEGIN: measure -->
                                <option value="{MEASURE.id}">{MEASURE.title}</option>
                            <!-- END: measure --> 
                            </select>
                        </td>
                    </tr>
                    <!-- BEGIN: showstatus -->
                    <tr>
                        <td>{LANG.status_action}</td>
                        <td>
                            <select class="form-control" name="status_action">
                            <!-- BEGIN: status_action -->
                                <option value="{STATUS_ACTION.key}"{STATUS_ACTION.sl}>{STATUS_ACTION.title}</option>
                            <!-- END: status_action -->
                            </select>
                            <input type="hidden" name="status_action" value="{ROW.status}" />
                        </td>
                    </tr>
                    <!-- END: showstatus -->
                    <tr>
                        <td colspan="2" class="text-center">
                            <input class="btn btn-primary" id="btn_save_history" onclick="save_history('{ROW.id}', '{USER_EVENT.id}');" value="Lưu dữ liệu" />
                        </td>
                    </tr>
        		</tbody>
        	</table>
        </form>
    	<table class="table table-striped table-bordered table-hover">
    		<thead>
    			<tr>
    				<th>{LANG.admin_action}</th>
    				<th>{LANG.date}</th>
                    <th>{LANG.content_history}</th>
                    <th>{LANG.eventtype_name}</th>
                    <th>{LANG.measure_name}</th>
    			</tr>
    		</thead>
    		<tbody>
    			<!-- BEGIN: loop -->
    			<tr>
    				<td> {VIEW.adminid} </td>
    				<td> {VIEW.addtime} </td>
                    <td> {VIEW.content} </td>
                    <td> {VIEW.eventtype} </td>
                    <td> {VIEW.measureid} </td>
    			</tr>
    			<!-- END: loop -->
    		</tbody>
    	</table>
    </div>
<!-- END: data_show -->