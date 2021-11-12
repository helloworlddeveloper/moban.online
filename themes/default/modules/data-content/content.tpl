<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_SITEURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<strong>{LANG.search_title}</strong>&nbsp;<input class="form-control" type="text" value="{keyword}" name="keyword" maxlength="255" />&nbsp;
	<select name="status" class="form-control">
		<option value=""> -{LANG.search_status}- </option>
		<!-- BEGIN: select_status -->
		<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
		<!-- END: select_status -->
	</select>
	<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
	<a class="btn btn-primary" href="{content_add}">{LANG.content_add}</a>
</form>
<br>

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.number}</th>
					<th>{LANG.title}</th>
					<th>{LANG.cat}</th>
					<th>Ngày tạo</th>
					<th>Ngày sửa</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.number} </td>
					<td> {VIEW.title} </td>
					<td> {VIEW.catid} </td>
					<th> {VIEW.addtime} </th>
					<th> {VIEW.edittime} </th>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: add -->
<div class="alert alert-success">Chúng tôi trân trọng các đóng góp của bạn để cùng xây dựng kho tài nguyên chung thêm phong phú.</div>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td> {LANG.title} </td>
					<td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.link}<br> <i> (đường link tuyệt đối) </i></td>
					<td><input class="form-control" type="text" name="link" value="{ROW.link}" /></td>
				</tr>
				<tr>
					<td>
                        {LANG.cat_parent} <span class="red">*</span>
					</td>
					<td>
						<select name="catid" class="form-control w300">
							<option value="0">--------</option>
							<!-- BEGIN: catid -->
							<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.title_show}</option>
							<!-- END: catid -->
						</select>
					</td>
				</tr>
				<tr>
					<td> {LANG.bodytext} </td>
					<td>
						{ROW.bodytext}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
		<input class="btn btn-primary" name="add" type="hidden" value="1" />
	</div>
</form>
<!-- END: add -->
<!-- END: main -->