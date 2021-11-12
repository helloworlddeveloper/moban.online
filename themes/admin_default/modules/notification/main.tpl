<!-- BEGIN: main -->
<div class="table-responsive form-inline">
	<table id="table_field_read" class="table table-striped table-bordered table-hover">
        <caption>{TABLE_CAPTION}</caption>
        <thead>
            <tr>
                <td>{LANG.notification_message}</td>
                <td>{LANG.notification_url}</td>
                <td>{LANG.notification_author}</td>
                <td>{LANG.notification_allowed_view}</td>
                <td>{LANG.notification_status}</td>
                <td style="width:100px;text-align:center">
                    {LANG.feature}
                </td>
            </tr>
        </thead>
        <tbody>
        <!-- BEGIN: row -->
            <tr>
                <td>{ROW.message}</td>
                <td>{ROW.url}</td>
                <td>{ROW.author}</td>
                <td>{ROW.notification_showview}</td>
                <td style="text-align:center">
                    <input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_chang_link_status({ROW.id})" />
                </td>
                <td style="text-align:center">
                    <span class="edit_icon"><a href="{EDIT_URL}">{GLANG.edit}</a></span>
                    &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_link_del({ROW.id});">{GLANG.delete}</a></span>
                </td>
            </tr>
        <!-- END: row -->
        </tbody>
        <tr class="footer">
            <td colspan="8">
                <!-- BEGIN: generate_page -->
                {GENERATE_PAGE}
                <!-- END: generate_page -->
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
function nv_chang_link_status(a) {
	nv_settimeout_disable("change_status" + a, 5E3);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&num=' + new Date().getTime(), 'changestatus=1&id=' + a, function(res) {
		if (res != "OK"){
		  alert(nv_is_change_act_confirm[2])
		}
		window.location.href = window.location.href
	});
}

function nv_link_del(a) {
    if( confirm(nv_is_del_confirm[0]) ){
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&num=' + new Date().getTime(), 'del=1&id=' + a, function(res) {
    		if (res == "OK"){
    		  window.location.href = window.location.href
    		}
    		else{
    		  alert(nv_is_del_confirm[2])
    		}
    	});
    }
}
</script>
<!-- END: main -->
