<!-- BEGIN: main -->
<div id="users">
	<div class="well">
		<form action="{FORM_ACTION}" method="get">
			<input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<input class="form-control" type="text" name="value" value="{SEARCH_VALUE}" id="f_value" placeholder="{LANG.search_key}" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<select class="form-control" name="method" id="f_method">
							<option value="">---{LANG.search_type}---</option>
							<!-- BEGIN: method -->
							<option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
							<!-- END: method -->
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<select class="form-control" name="usactive">
							<option value="-1">{LANG.usactive}</option>
							<!-- BEGIN: usactive -->
							<option value="{USACTIVE.key}"{USACTIVE.selected}>{USACTIVE.value}</option>
							<!-- END: usactive -->
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<input class="btn btn-primary" name="search" type="submit" value="{LANG.submit}" />&nbsp;
						<span id="loading_bar"><input type="button" class="btn btn-primary" name="data_export" value="{LANG.export}" /></span>
					</div>
				</div>
			</div>
			<div class="alert alert-info">{LANG.note_export}</div>
		</form>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}
			</caption>
			<thead>
				<tr>
					<!-- BEGIN: head_td -->
					<th><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></th>
					<!-- END: head_td -->
					<th class="text-center">{LANG.active}</th>
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
				<!-- BEGIN: xusers -->
				<tr>
					<td>{CONTENT_TD.userid}</td>
					<td>
						<!-- BEGIN: is_admin --> <img style="vertical-align: middle;" alt="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" /> <!-- END: is_admin --> <a href="{CONTENT_TD.link}" target="_blank">{CONTENT_TD.username}</a>
					</td>
					<td>{CONTENT_TD.full_name}</td>
					<td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
					<td>{CONTENT_TD.money_in}</td>
					<td>{CONTENT_TD.money_out}</td>
					<td>{CONTENT_TD.money}</td>
					<td class="text-center"><input type="checkbox" name="active" id="change_status_{CONTENT_TD.userid}" value="{CONTENT_TD.userid}" {CONTENT_TD.checked}{CONTENT_TD.disabled} /></td>
				</tr>
				<!-- END: xusers -->
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
 function nv_data_export(set_export, nextid) {
     $.ajax({
         type : "POST",
         url : script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export&nocache=" + new Date().getTime(),
         data : "step=1&set_export=" + set_export + '&nextid=' + nextid,
         success : function(response) {
             var data = response.split('_');
             if ( data[0] == "NEXT") {
                 nv_data_export(0, data[1]);
             } else if (data[0] == "COMPLETE") {
                 $("#loading_bar").hide();
                 alert('{LANG.export_complete}');
                 window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
             } else {
                 $("#loading_bar").hide();
                 alert(response);
                 window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
             }
         }
     });
 }


 $("input[name=data_export]").click(function() {
     $("input[name=data_export]").attr("disabled", "disabled");
     $('#loading_bar').html('<center>{LANG.export_note}<br /><br /><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="" /></center>');
     nv_data_export(1, 0);
 });
 $("input[name=data_paymented]").click(function() {
     if( confirm('Bạn có chắc muốn xác nhận đã thanh toán hoa hồng? Nếu đồng ý, toàn bộ số tiền của thành viên đã thanh toán trong tháng sẽ bị reset về 0VNĐ!')){
         $.ajax({
             type : "POST",
             url : "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=statistic&nocache=" + new Date().getTime(),
             data : "act_payment=1",
             success : function(response) {
                 if ( response == 'OK' ) {
                     window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=statistic';
                 } else{
                     alert(response);
                 }
             }
         });
     }
 });
 </script>
 <!-- END: main -->