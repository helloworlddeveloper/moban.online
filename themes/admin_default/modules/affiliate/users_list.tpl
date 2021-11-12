<!-- BEGIN: main -->
<!-- BEGIN: cat_title -->
<div style="background:#eee;padding:10px">
	{CAT_TITLE}
</div>
<!-- END: cat_title -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col span="4" style="white-space: nowrap;" />
		<col class="w250" />
		<col style="white-space: nowrap;" />
		<thead>
			<tr>
				<th class="text-center">Mã NV&nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.code_note}">&nbsp;</em></th>
				<th class="text-center">{LANG.fullname}</th>
				<th class="text-center">{LANG.salary_day}</th>
				<th class="text-center">{LANG.benefit}</th>
				<th class="text-center">{LANG.birthday}</th>
				<th class="text-center">{LANG.email}</th>
				<th class="text-center">{LANG.mobile}</th>
				<th class="text-center">{LANG.address}</th>
                <th class="text-center">{LANG.active}</th>
				<th class="text-center">{LANG.permission}</th>
				<th class="text-center">{LANG.functional}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.code}</td>
				<td><a href="{ROW.link}"><strong>{ROW.fullname}</strong>
				<!-- BEGIN: numsubcat -->
				<span class="red">&nbsp;({NUMSUBCAT})</span>
				<!-- END: numsubcat -->
				</a></td>
				<td class="text-center">{ROW.salary_day}</td>
				<td class="text-center">{ROW.benefit}</td>
				<td class="text-center">{ROW.birthday}</td>
				<td class="text-center">{ROW.email}</td>
				<td class="text-center">{ROW.mobile}</td>
				<td class="text-center">{ROW.datatext.address}</td>
                <td class="text-center"><input type="checkbox" name="active" onclick="nv_chang_users_status({ROW.userid})" id="change_status_{ROW.userid}" value="{ROW.userid}"{ROW.checked}{ROW.disabled} /></td>
				<td class="text-center"><input type="checkbox" name="active" onclick="nv_chang_users_permission({ROW.userid})" id="change_permission_{ROW.userid}" value="{ROW.userid}"{ROW.permissionchecked}{ROW.disabled} /></td>
				<td class="text-center">{ROW.admin_edit} {ROW.admin_delete}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: data -->
<!-- END: main -->