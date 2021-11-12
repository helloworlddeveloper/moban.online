<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="reg_id" value="{ROW.reg_id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
                <tr>
                    <td>Họ và tên<span style="color:#f00">(*)</span></td>
					<td><input class="form-control" type="text" name="fullname" placeholder="Nhập họ tên học sinh học" value="{ROW.reg_full_name}" /></td>
				</tr>
                <tr>
                    <td>Điện thoại<span style="color:#f00">(*)</span></td>
					<td><input class="form-control" type="text" name="phone" placeholder="Điện thoại" value="{ROW.reg_phone}" /></td>
				</tr>
                <tr>
                    <td>Địa chỉ email</td>
					<td><input class="form-control" type="text" name="email" placeholder="Nhập địa chỉ mail liên hệ" value="{ROW.reg_email}" /></td>
				</tr>
                <tr>
                    <td>Địa chỉ liên hệ</td>
					<td><input class="form-control" type="text" name="address" placeholder="Địa chỉ liên hệ" value="{ROW.reg_address}" /></td>
				</tr>
                <tr>
                    <td>Ghi chú</td>
					<td><textarea class="form-control" style="width:100%;height:60px" name="note">{ROW.note}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="Đăng ký" /></div>
</form>
<!-- END: main -->