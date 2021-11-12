<!-- BEGIN: main -->

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
				<th class="text-center">{LANG.domain_name}</th>
				<th class="text-center">{LANG.site_title}</th>
				<th class="text-center">{LANG.site_email}</th>
				<th class="text-center">{LANG.site_mobile}</th>
				<th class="text-center">Người giới thiệu</th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<!-- BEGIN: loop -->
			<tr">
			<td><a href="http://{ROW.domain}" target="_blank">{ROW.domain}</a></td>
			<td class="text-left"><a href="http://{ROW.domain}/admin" target="_blank">{ROW.title}</a></td>
			<td>{ROW.email}</td>
			<td>{ROW.mobile}</td>
			<td>{ROW.mobile_refer}</td>
			<td class="text-center">
				<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{ROW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
			</td>
			</tr>
			<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->