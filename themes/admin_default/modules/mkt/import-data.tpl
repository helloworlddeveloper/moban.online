<!-- BEGIN: main -->
<!-- BEGIN: view -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
        <caption>Danh sách các dữ liệu không ghi vào hệ thống do phát hiện trùng lặp nội dung về tên và SĐT phụ huynh</caption>
		<thead>
			<tr>
				<th>{LANG.number}</th>
                <th>Lớp</th>
				<th>Trường</th>
                <th>Tên học sinh</th>
                <th>Tên phụ huynh</th>
				<th>SĐT phụ huyh</th>
				<th>SĐT học sinh</th>
				<th>Năm học</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">{NV_GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {VIEW.number} </td>
                <td> {VIEW.class} </td>
				<td> {VIEW.school} </td>
                <td> {VIEW.student_fullname} </td>
                <td> {VIEW.parent_fullname} </td>
				<td> {VIEW.phone_student} </td>
				<td> {VIEW.phone_parent} </td>
                <td> {VIEW.phone_student} </td>
                <td> {VIEW.school_year} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: view -->
<div class="alert alert-warning">{LANG.import_note}</div>
<!-- BEGIN: read -->
<br />
{LANG.read_note}
<div class="table-responsive">
	<table id="table_field_read" class="table table-striped table-bordered table-hover">
		<colgroup>
			<col style="width: 35px" />
			<col span="2" />
		</colgroup>
		<thead>
			<tr>
				<td>&nbsp;</td>
				<td><strong>{LANG.read_filename}</strong></td>
				<td><strong>{LANG.read_filesize}</strong></td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><input type="checkbox" name="readfiledata[]"
				value="{DATA.file_name_base64}"></td>
				<td>{DATA.file_name}</td>
				<td>{DATA.file_size}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
    <table class="table table-striped table-bordered table-hover">
		<tbody>
  	         <tfoot>
    			<tr>
    				<td colspan="2">
                        <div id="loading_bar"></div><input type="button" class="btn btn-primary" name="submitfiledata" value="{LANG.read_submit}" />
                    </td>
    			</tr>
    		</tfoot>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	function nv_readfiledata(listfile) {
		$.ajax({
			type : "POST",
			url : "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&nocache=" + new Date().getTime(),
			data : "step=2&listfile=" + listfile,
			success : function(response) {
				if (response == "OK_GETFILE") {
					nv_readfiledata('');
				} else if (response == "OK_COMPLETE") {
					$("#table_field_read").hide();
					if (confirm('{LANG.read_complete}')) {
						window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + "&" + nv_fc_variable + "={OP}&step=3";
					}
				} else {
					alert(response);
					$("#table_field_read").hide();
				}
			},
			error : function(x, e) {
				if (x.status == 0) {
					alert('You are offline!!\n Please Check Your Network.');
				} else if (x.status == 404) {
					alert('Requested URL not found.');
				} else if (x.status == 500) {
					alert('{LANG.read_error_memory_limit}');
				} else if (e == 'timeout') {
					alert('Request Time out.');
				} else {
					alert('Unknow Error.\n' + x.responseText);
				}
				$("#table_field_read").hide();
			}
		});
	}


	$("input[name=submitfiledata]").click(function() {
		var listfile = '';
		$("input[name=\'readfiledata[]\']:checked").each(function() {
			listfile = listfile + '@' + $(this).val();
		});
		if (listfile != '' ) {
			$("#table_field_read").html("<center><img src='{NV_BASE_SITEURL}images/load_bar.gif' alt='' /></center>");
			nv_readfiledata( listfile );
		}
	});
	//]]>
</script>
<!-- END: read -->
<!-- END: main -->