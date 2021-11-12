<!-- BEGIN: main -->
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
			<td> <strong>{LANG.email}</strong> </td>
			<td>{ROW.email}</td>
			<td> <strong>{LANG.sex}</strong> </td>
			<td>{ROW.sex}</td>
		</tr>
		<tr>
			<td> <strong>{LANG.mobile}</strong></td>
			<td>{ROW.mobile}</td>
			<td><strong>{LANG.status}</strong></td>
			<td>{ROW.status_text}</td>
		</tr>
		<tr>
			<td> <strong>{LANG.address}</strong> </td>
			<td>{ROW.address}</td>
            <td> <strong>{LANG.from_by}</strong> </td>
			<td>{ROW.from_by}</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function() {
	$("#from,#to").datepicker({
		showOn : "both",
		dateFormat : "dd.mm.yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonText : '{LANG.select}',
		showButtonPanel : true,
		showOn : 'focus'
	});
	$('input[name=clear]').click(function() {
		$('#filter-form .text').val('');
		$('input[name=q]').val('{LANG.filter_enterkey}');
	});
	$('input[name=action]').click(function() {
		var f_q = $('input[name=q]').val();
		var f_from = $('input[name=from]').val();
		var f_to = $('input[name=to]').val();
		var f_user = $('select[name=user]').val();
		if (f_q != '' || f_from != '' || f_to != '' || f_user != '' ) {
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&filter=1&checksess={checksess}&q=' + f_q + '&from=' + f_from + '&to=' + f_to + '&user=' + f_user + '&studentid={studentid}&parentid={parentid}';
		} else {
			alert('{LANG.filter_err_submit}');
		}
	});
});
</script>
<!-- BEGIN: allow_add -->
<div class="table-responsive">
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <table class="table table-striped table-bordered table-hover">
            <caption>Ghi thêm lịch sử chăm sóc</caption>
    		<tbody>
    			<tr>
    				<td>{LANG.content_history}</td>
    				<td><textarea class="form-control" name="note" style="width:100%;height:60px"></textarea></td>
    			</tr>
				<tr>
					<td>{LANG.status_accept}</td>
					<td>
						<select class="form-control" name="status_accept">
							<option value="">----</option>
							<!-- BEGIN: status_accept -->
							<option value="{STATUS.key}">{STATUS.value}</option>
							<!-- END: status_accept -->
						</select>
					</td>
				</tr>
                <tr>
                    <td>{LANG.eventtype_name}</td>
                    <td>
                        <select class="form-control" name="eventtype">
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
                        <input class="btn btn-primary" onclick="save_history({id});" value="Save" />
                    </td>
                </tr>
    		</tbody>
    	</table>
    </form>
</div>    
<!-- END: allow_add -->
    <form class="form-inline" id="filter-form" method="get" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            	<tbody>
            		<tr>
        				<td>
                            <input style="width:250px" type="text" class="form-control" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_enterkey}" />
        					<input class="form-control" value="{DATA_SEARCH.from}" type="text" id="from" name="from" readonly="readonly" style="width:80px" placeholder="{LANG.filter_from}" />
        					<input class="form-control" value="{DATA_SEARCH.to}" type="text" id="to" name="to" readonly="readonly" style="width:80px" placeholder="{LANG.filter_to}" />
                            <input type="button" name="action" value="{LANG.filter_action}" class="btn btn-default" />
                    		<input type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DISABLE} class="btn btn-default"/>
                    		<input type="button" name="clear" value="{LANG.filter_clear}" class="btn btn-default"/>
        				</td>
            		</tr>
            	</tbody>
            </table>
        </div>
    </form>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.admin_action}</th>
				<th>{LANG.date}</th>
                <th>{LANG.content_history}</th>
                <th>{LANG.eventtype_name}</th>
                <th>{LANG.measure_name}</th>
                <th>{LANG.remkt_time}</th>
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
				<td> {VIEW.adminid} </td>
				<td> {VIEW.addtime} </td>
                <td> <a href="{VIEW.link_users}">{VIEW.content}</a> </td>
                <td> {VIEW.eventtype} </td>
                <td> {VIEW.measureid} </td>
                <td> {VIEW.remkt_time} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
    function save_history(customerid){
        var note = $('textarea[name=note]').val();
        if( note == '' ){
            alert('Bạn chưa nhập nội dung ghi chú');
            $('textarea[name=note]').focus();
            return;
        }else{
            var eventtype = $('select[name=eventtype]').val();
            var measureid = $('select[name=measureid]').val();
            var status_accept = $('select[name=status_accept]').val();
            if( status_accept == ''){
                alert('Bạn chưa chọn trạng thái cuộc gọi!');
                return;
            }
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'save=1&customerid=' + customerid + '&note=' + note + '&eventtype='+eventtype+'&measureid='+measureid+'&status_accept='+ status_accept +'&num=' + nv_randomPassword( 8 ), function(res) {
        		if( res == "OK"){
        		  window.location.href=window.location.href;
        		}else{
        		  alert('Lỗi không lưu nội dung');
        		}
        	});
        }
    }
</script>
<!-- END: main -->