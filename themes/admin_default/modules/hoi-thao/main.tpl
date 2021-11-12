<!-- BEGIN: main -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<strong>{LANG.search_title}</strong>&nbsp;<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
	<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
</form>
<br>

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Ngày đăng ký</th>
					<th>Họ tên</th>
					<th>SDT liên hệ</th>
					<th>Email</th>
                    <th>Địa chỉ</th>
					<th>Domain giới thiệu</th>
                    <th>Ghi chú</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="11">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.add_time} </td>
					<td> {VIEW.reg_full_name} </td>
					<td> {VIEW.reg_phone} </td>
					<td> {VIEW.reg_email} </td>
                    <td> {VIEW.reg_address} </td>
					<td>{VIEW.domain}</td>
					<td> {VIEW.note} </td>
					<td> 
                        <select onchange="nv_reg_chang_status(this.value, {VIEW.reg_id})" class="form-control" name="status">
                            <!-- BEGIN: status -->
                            <option value="{STATUS.key}"{STATUS.sl}>{STATUS.val}</option>
                            <!-- END: status-->
                        </select>
                    </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
    function nv_reg_chang_status(status,id){
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'act=status&status=' + status + '&id=' + id, function(res) {
			if( res == 'OK'){
                window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main'; 
			}else{
                alert(res);
			}
            
			return;
		});
    }
</script>
<!-- END: main -->