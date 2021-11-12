<!-- BEGIN: main -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<strong>{LANG.search_title}</strong>&nbsp;<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
    <select class="form-control" name="status">
        <option value="">---All---</option>
		<!-- BEGIN: select_status -->
		<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
		<!-- END: select_status -->
	</select>
    <select class="form-control" name="modulename">
        <option value="">---{LANG.modulename}---</option>
		<!-- BEGIN: modulename -->
		<option value="{OPTION.key}" {OPTION.sl}>{OPTION.title}</option>
		<!-- END: modulename -->
	
	<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
</form>
<br />

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.number}</th>
					<th>{LANG.fullname}</th>
					<th>{LANG.phone}</th>
                    <th>{LANG.modulename}</th>
                    <th>{LANG.url_reg}</th>
					<th>{LANG.add_time}</th>
					<th>&nbsp;</th>
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
					<td> {VIEW.student_name} </td>
					<td> {VIEW.mobile} </td>
                    <td> {VIEW.modulename} </td>
                    <td> {VIEW.url_reg} </td>
					<td> {VIEW.add_time} </td>
					<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->
